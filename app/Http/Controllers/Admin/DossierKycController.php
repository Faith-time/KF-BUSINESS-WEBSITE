<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DossierKyc;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DossierKycController extends Controller
{
    public function index()
    {
        $dossiers = DossierKyc::query()
            ->with('user')
            ->latest('created_at')
            ->paginate(20);

        $statistiques = [
            'total' => DossierKyc::count(),
            'approuves' => DossierKyc::where('statut', 'approuvé')->count(),
            'rejetes' => DossierKyc::where('statut', 'rejeté')->count(),
            'enRevision' => DossierKyc::where('statut', 'en_révision')->count(),
            'soumis' => DossierKyc::where('statut', 'soumis')->count(),
        ];

        return Inertia::render('Admin/DossiersKYC/Index', [
            'dossiers' => $dossiers,
            'statistiques' => $statistiques,
        ]);
    }

    public function show(DossierKyc $dossier)
    {
        $dossier->load('user', 'verifyBy');

        return Inertia::render('Admin/DossiersKYC/Show', [
            'dossier' => $dossier,
            'user' => $dossier->user,
            'documentsSupports' => $dossier->documents_supports ?? [],
        ]);
    }

    public function review(DossierKyc $dossier)
    {
        $dossier->update(['statut' => 'en_révision']);

        return back()->with('success', 'Dossier marqué comme en révision.');
    }

    public function approuver(DossierKyc $dossier)
    {
        $validated = request()->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $dossier->approuver(Auth::id(), $validated['notes'] ?? null);

        // TODO: Envoyer notification à l'utilisateur

        return back()->with('success', 'Dossier KYC approuvé.');
    }

    public function rejeter(DossierKyc $dossier)
    {
        $validated = request()->validate([
            'raison' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $dossier->rejeter($validated['raison'], Auth::id(), $validated['notes'] ?? null);

        // TODO: Envoyer notification de rejet à l'utilisateur

        return back()->with('success', 'Dossier KYC rejeté. Un email de notification a été envoyé à ' . $dossier->user->email);
    }

    public function requestRevision(DossierKyc $dossier)
    {
        $validated = request()->validate([
            'raison' => 'required|string|max:500',
        ]);

        $dossier->update([
            'statut' => 'nécessite_révision',
        ]);

        // TODO: Envoyer notification à l'utilisateur

        return back()->with('success', 'Demande de révision envoyée à l\'investisseur.');
    }

    public function export()
    {
        // Exporter les dossiers KYC approuvés
        $dossiers = DossierKyc::where('statut', 'approuvé')
            ->with('user')
            ->get();

        return response()->json($dossiers);
    }

    public function batch()
    {
        $validated = request()->validate([
            'action' => 'required|in:approuver,rejeter,reviewer',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:dossier_kycs,id',
        ]);

        $dossiers = DossierKyc::whereIn('id', $validated['ids'])->get();

        switch ($validated['action']) {
            case 'approuver':
                foreach ($dossiers as $dossier) {
                    $dossier->approuver(Auth::id());
                }
                return back()->with('success', count($dossiers) . ' dossier(s) approuvé(s).');

            case 'rejeter':
                // Nécessite une raison, donc pas d'action batch pour rejeter
                return back()->with('error', 'Utilisez l\'action individuelle pour rejeter.');

            case 'reviewer':
                foreach ($dossiers as $dossier) {
                    $dossier->update(['statut' => 'en_révision']);
                }
                return back()->with('success', count($dossiers) . ' dossier(s) marqué(s) en révision.');
        }
    }

    public function downloadDocuments(DossierKyc $dossier)
    {
        if (!$dossier->documents_supports) {
            return back()->with('error', 'Aucun document à télécharger.');
        }

        // TODO: Créer un ZIP avec tous les documents
        // Pour l'instant, on retourne les chemins
        return response()->json($dossier->documents_supports);
    }
}
