<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Projet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::query()
            ->with('projet', 'createur')
            ->latest('created_at')
            ->paginate(20);

        return Inertia::render('Admin/Documents/Index', [
            'documents' => $documents,
        ]);
    }

    public function create()
    {
        $projets = Projet::all();

        return Inertia::render('Admin/Documents/Create', [
            'projets' => $projets,
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:présentation,prospectus,contrat,rapport_financier,attestation,certificat_participation,avis_paiement,autre',
            'description' => 'nullable|string',
            'projet_id' => 'nullable|exists:projets,id',
            'fichier' => 'required|file|max:102400',
            'visible_investisseurs' => 'boolean',
            'visible_public' => 'boolean',
            'visible_comptables' => 'boolean',
            'date_publication' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_publication',
            'tags' => 'nullable|array',
        ]);

        $disk = Storage::disk('documents');
        $path = $validated['fichier']->store('documents', 'documents');

        Document::create([
            'titre' => $validated['titre'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'projet_id' => $validated['projet_id'],
            'createur_id' => Auth::id(),
            'chemin_fichier' => $path,
            'nom_fichier_original' => $validated['fichier']->getClientOriginalName(),
            'extension' => $validated['fichier']->getClientOriginalExtension(),
            'taille_bytes' => $validated['fichier']->getSize(),
            'mime_type' => $validated['fichier']->getMimeType(),
            'visible_investisseurs' => $validated['visible_investisseurs'] ?? false,
            'visible_public' => $validated['visible_public'] ?? false,
            'visible_comptables' => $validated['visible_comptables'] ?? false,
            'date_publication' => $validated['date_publication'],
            'date_expiration' => $validated['date_expiration'],
            'tags' => $validated['tags'] ?? [],
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document créé.');
    }

    public function edit(Document $document)
    {
        $projets = Projet::all();

        return Inertia::render('Admin/Documents/Edit', [
            'document' => $document,
            'projets' => $projets,
        ]);
    }

    public function update(Document $document)
    {
        $validated = request()->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:présentation,prospectus,contrat,rapport_financier,attestation,certificat_participation,avis_paiement,autre',
            'description' => 'nullable|string',
            'visible_investisseurs' => 'boolean',
            'visible_public' => 'boolean',
            'visible_comptables' => 'boolean',
            'date_expiration' => 'nullable|date',
            'tags' => 'nullable|array',
        ]);

        $document->update($validated);

        return back()->with('success', 'Document mis à jour.');
    }

    public function show(Document $document)
    {
        return Inertia::render('Admin/Documents/Show', [
            'document' => $document,
            'statistiques' => [
                'tailleEnMo' => $document->tailleEnMo(),
                'nombreTelechargements' => $document->nombre_telechargements,
                'estExpire' => $document->estExpire(),
                'estPublie' => $document->estPublie(),
            ],
        ]);
    }

    public function download(Document $document)
    {
        $document->incrementerTelechargements();

        return Storage::disk('documents')->download($document->chemin_fichier, $document->nom_fichier_original);
    }

    public function publish(Document $document)
    {
        $validated = request()->validate([
            'date_publication' => 'nullable|date',
        ]);

        $document->publier($validated['date_publication'] ?? null);

        return back()->with('success', 'Document publié.');
    }

    public function unpublish(Document $document)
    {
        $document->update([
            'visible_investisseurs' => false,
            'visible_public' => false,
        ]);

        return back()->with('success', 'Document dépublié.');
    }

    public function expire(Document $document)
    {
        $validated = request()->validate([
            'date_expiration' => 'required|date|after:' . now()->toDateString(),
        ]);

        $document->faire_expirer($validated['date_expiration']);

        return back()->with('success', 'Date d\'expiration définie.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('documents')->delete($document->chemin_fichier);
        $document->delete();

        return back()->with('success', 'Document supprimé.');
    }

    public function bulk()
    {
        $validated = request()->validate([
            'action' => 'required|in:publier,depublier,supprimer',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:documents,id',
        ]);

        $documents = Document::whereIn('id', $validated['ids'])->get();

        switch ($validated['action']) {
            case 'publier':
                foreach ($documents as $doc) {
                    $doc->publier();
                }
                return back()->with('success', count($documents) . ' document(s) publié(s).');

            case 'depublier':
                foreach ($documents as $doc) {
                    $doc->unpublish();
                }
                return back()->with('success', count($documents) . ' document(s) dépublié(s).');

            case 'supprimer':
                foreach ($documents as $doc) {
                    Storage::disk('documents')->delete($doc->chemin_fichier);
                    $doc->delete();
                }
                return back()->with('success', count($documents) . ' document(s) supprimé(s).');
        }
    }
}
