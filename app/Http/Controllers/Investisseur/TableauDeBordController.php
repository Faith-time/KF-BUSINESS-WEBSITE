<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TableauDeBordController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user()->load('souscriptions.projet', 'souscriptions.paiements', 'dossierKyc');

        $souscriptionsApprouvees = $user->souscriptions()
            ->where('statut', 'approuvée')
            ->with('projet', 'ticketInvestissement')
            ->get();

        $paiementsRecents = $user->paiements()
            ->where('statut', 'complété')
            ->with('souscription.projet')
            ->latest('date_paiement')
            ->limit(5)
            ->get();

        $totalInvesti = $souscriptionsApprouvees->sum('montant_souscrit');
        $totalVerse = $souscriptionsApprouvees->sum('montant_paye');
        $montantRestant = $souscriptionsApprouvees->sum('montant_restant');

        return Inertia::render('Investisseur/TableauDeBord', [
            'user' => $user,

            'souscriptions' => $souscriptionsApprouvees,
            'paiementsRecents' => $paiementsRecents,
            'statistiques' => [
                'totalInvesti' => $totalInvesti,
                'totalVerse' => $totalVerse,
                'montantRestant' => $montantRestant,
                'nombreProjets' => $souscriptionsApprouvees->count(),
                'kyceApprouve' => $user->dossierKyc?->statut === 'approuvé',
            ],
        ]);
    }
}
