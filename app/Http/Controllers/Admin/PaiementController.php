<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::query()
            ->with('souscription.investisseur', 'souscription.projet', 'methodePaiement')
            ->latest('date_paiement')
            ->paginate(20);

        $statistiques = [
            'total' => Paiement::count(),
            'completes' => Paiement::where('statut', 'complété')->count(),
            'enAttente' => Paiement::where('statut', 'en_attente')->count(),
            'echoues' => Paiement::where('statut', 'échoué')->count(),
            'montantTotal' => Paiement::where('statut', 'complété')->sum('montant'),
            'montantFrais' => Paiement::where('statut', 'complété')->sum('frais'),
        ];

        return Inertia::render('Admin/Paiements/Index', [
            'paiements' => $paiements,
            'statistiques' => $statistiques,
        ]);
    }

    public function show(Paiement $paiement)
    {
        $paiement->load('souscription.investisseur', 'souscription.projet', 'methodePaiement');

        return Inertia::render('Admin/Paiements/Show', [
            'paiement' => $paiement,
        ]);
    }

    public function confirmer(Paiement $paiement)
    {
        if ($paiement->statut !== 'en_attente' && $paiement->statut !== 'traitement') {
            return back()->with('error', 'Ce paiement a déjà été traité.');
        }

        $paiement->confirmer();

        // TODO: Envoyer notification de confirmation

        return back()->with('success', 'Paiement confirmé.');
    }

    public function rejeter(Paiement $paiement)
    {
        $validated = request()->validate([
            'raison' => 'required|string|max:500',
        ]);

        $paiement->echec($validated['raison']);

        // TODO: Envoyer notification de rejet

        return back()->with('success', 'Paiement rejeté.');
    }

    public function rembourser(Paiement $paiement)
    {
        if ($paiement->statut !== 'complété') {
            return back()->with('error', 'Seuls les paiements complétés peuvent être remboursés.');
        }

        $validated = request()->validate([
            'raison' => 'required|string|max:500',
        ]);

        $paiement->rembourser();

        // TODO: Envoyer notification de remboursement

        return back()->with('success', 'Paiement remboursé.');
    }

    public function markAsProcessing(Paiement $paiement)
    {
        if ($paiement->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les paiements en attente peuvent être marqués comme en traitement.');
        }

        $paiement->update(['statut' => 'traitement']);

        return back()->with('success', 'Paiement marqué comme en traitement.');
    }

    public function updateReferencne(Paiement $paiement)
    {
        $validated = request()->validate([
            'reference_externe' => 'required|string|max:255',
        ]);

        $paiement->update(['reference_externe' => $validated['reference_externe']]);

        return back()->with('success', 'Référence mise à jour.');
    }

    public function batch()
    {
        $validated = request()->validate([
            'action' => 'required|in:confirmer,rejeter,traitement',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:paiements,id',
        ]);

        $paiements = Paiement::whereIn('id', $validated['ids'])->get();

        switch ($validated['action']) {
            case 'confirmer':
                foreach ($paiements as $paiement) {
                    if ($paiement->statut === 'en_attente' || $paiement->statut === 'traitement') {
                        $paiement->confirmer();
                    }
                }
                return back()->with('success', count($paiements) . ' paiement(s) confirmé(s).');

            case 'traitement':
                foreach ($paiements as $paiement) {
                    if ($paiement->statut === 'en_attente') {
                        $paiement->update(['statut' => 'traitement']);
                    }
                }
                return back()->with('success', count($paiements) . ' paiement(s) marqué(s) en traitement.');

            case 'rejeter':
                return back()->with('error', 'Utilisez l\'action individuelle pour rejeter.');
        }
    }

    public function filterByPeriod()
    {
        $validated = request()->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $paiements = Paiement::query()
            ->with('souscription.investisseur', 'souscription.projet', 'methodePaiement')
            ->whereBetween('date_paiement', [$validated['date_debut'], $validated['date_fin']])
            ->latest('date_paiement')
            ->get();

        return response()->json($paiements);
    }

    public function filterByStatus()
    {
        $status = request('status');

        $paiements = Paiement::query()
            ->with('souscription.investisseur', 'souscription.projet', 'methodePaiement')
            ->where('statut', $status)
            ->latest('date_paiement')
            ->paginate(20);

        return response()->json($paiements);
    }
}
