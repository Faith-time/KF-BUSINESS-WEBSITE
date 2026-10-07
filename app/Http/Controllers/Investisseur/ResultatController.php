<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use App\Models\Dividende;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ResultatController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $dividendesReus = $user->dividendes()
            ->where('statut', 'versé')
            ->with('souscription.projet')
            ->latest('date_paiement_reel')
            ->get();

        $dividendesAVenir = $user->dividendes()
            ->whereIn('statut', ['planifié', 'approuvé'])
            ->with('souscription.projet')
            ->latest('date_paiement_prevu')
            ->get();

        $totalRecu = $dividendesReus->sum('montant_net');
        $totalAVenir = $dividendesAVenir->sum('montant_brut');

        return Inertia::render('Investisseur/Resultats', [
            'dividendesReus' => $dividendesReus,
            'dividendesAVenir' => $dividendesAVenir,
            'statistiques' => [
                'totalRecu' => $totalRecu,
                'totalAVenir' => $totalAVenir,
                'nombreDistributions' => $dividendesReus->count(),
            ],
        ]);
    }
}
