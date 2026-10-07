<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\Dividende;
use Inertia\Inertia;

class TableauDeBordController extends Controller
{
    public function __invoke()
    {
        $paiementsJour = Paiement::query()
            ->whereDate('date_paiement', today())
            ->with('souscription.investisseur', 'souscription.projet')
            ->get();

        $paiementsEnAttente = Paiement::query()
            ->where('statut', 'en_attente')
            ->with('souscription.investisseur', 'souscription.projet')
            ->count();

        $souscriptionsEnAttente = Souscription::query()
            ->where('statut', 'en_attente')
            ->count();

        $montantCollecte = Paiement::query()
            ->where('statut', 'complété')
            ->sum('montant');


        $dividendesAVenir = Dividende::query()
            ->where('statut', 'approuvé')
            ->where('date_paiement_prevu', '<=', now()->addDays(30))
            ->sum('montant_net');

        return Inertia::render('Comptable/TableauDeBord', [
            'paiementsJour' => $paiementsJour,
            'statistiques' => [
                'paiementsEnAttente' => $paiementsEnAttente,
                'souscriptionsEnAttente' => $souscriptionsEnAttente,
                'montantCollecte' => $montantCollecte,
                'dividendesAVenir' => $dividendesAVenir,
            ],
        ]);
    }
}
