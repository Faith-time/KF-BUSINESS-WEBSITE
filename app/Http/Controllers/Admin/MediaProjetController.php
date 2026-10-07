<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\MediaProjet;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MediaProjetController extends Controller
{
    public function index(Projet $projet)
    {
        $medias = $projet->mediaProjet()->paginate(20);

        return Inertia::render('Admin/Medias/Index', [
            'projet' => $projet,
            'medias' => $medias,
        ]);
    }

    public function create(Projet $projet)
    {
        return Inertia::render('Admin/Medias/Create', [
            'projet' => $projet,
        ]);
    }

    public function store(Projet $projet)
    {
        $validated = request()->validate([

            'type' => 'required|in:image,vidéo,document,audio',
            'fichier' => 'required|file|mimes:jpeg,png,mp4,pdf,mp3|max:51200',
            'description' => 'nullable|string',
        ]);

        $path = $validated['fichier']->store('projets/' . $projet->id, 'media');

        $projet->mediaProjet()->create([
            'type' => $validated['type'],
            'chemin_fichier' => $path,
            'nom_fichier_original' => $validated['fichier']->getClientOriginalName(),
            'mime_type' => $validated['fichier']->getMimeType(),
            'taille_bytes' => $validated['fichier']->getSize(),
            'description' => $validated['description'],
        ]);

        return back()->with('success', 'Média ajouté.');
    }

    public function destroy(Projet $projet, MediaProjet $media)
    {
        Storage::disk('media')->delete($media->chemin_fichier);
        $media->delete();

        return back()->with('success', 'Média supprimé.');
    }
}
