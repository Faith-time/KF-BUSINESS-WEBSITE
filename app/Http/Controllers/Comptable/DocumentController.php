<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::query()
            ->where('visible_comptables', true)
            ->with('projet', 'createur')
            ->latest('date_publication')
            ->paginate(15);

        return Inertia::render('Comptable/Documents/Index', [
            'documents' => $documents,
        ]);
    }

    public function download(Document $document)
    {
        if (!$document->visible_comptables) {
            abort(403);
        }

        $document->incrementerTelechargements();

        return Storage::disk('documents')->download($document->chemin_fichier, $document->nom_fichier_original);
    }
}
