<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\Souscription;
use App\Models\Paiement;
use App\Models\User;
use Inertia\Inertia;

class TableauDeBordController extends Controller
{
    public function __invoke()
    {
        $projets = Projet::count();
        $investisseurs = User::role('investisseur')->count();
        $montantTotal = Paiement::where('statut', 'complété')->sum('montant');
        $souscriptionsEnAttente = Souscription::where('statut', 'en_attente')->count();

        $projetsRecents = Projet::with('promoteur', 'mediaProjet')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $paiementsRecents = Paiement::with('souscription.investisseur', 'souscription.projet')
            ->where('statut', 'complété')
            ->latest('date_confirmation')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/TableauDeBord', [

            'statistiques' => [
                'totalProjets' => $projets,
                'totalInvestisseurs' => $investisseurs,
                'montantTotal' => $montantTotal,
                'souscriptionsEnAttente' => $souscriptionsEnAttente,
            ],
            'projetsRecents' => $projetsRecents,
            'paiementsRecents' => $paiementsRecents,
        ]);
    }
}
