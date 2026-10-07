<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PortefeuilleController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user()->load('souscriptions.projet', 'souscriptions.dividendes');

        $souscriptions = $user->souscriptions()
            ->where('statut', 'approuvée')
            ->with('projet', 'dividendes')
            ->get();

        $totalInvesti = $souscriptions->sum('montant_souscrit');
        $totalVerse = $souscriptions->sum('montant_paye');
        $totalDividendes = $user->dividendes()->where('statut', 'versé')->sum('montant_net');

        $performance = $totalInvesti > 0 ? (($totalDividendes / $totalInvesti) * 100) : 0;

        return Inertia::render('Investisseur/Portefeuille', [
            'souscriptions' => $souscriptions,
            'statistiques' => [
                'totalInvesti' => $totalInvesti,
                'totalVerse' => $totalVerse,
                'totalDividendes' => $totalDividendes,
                'performance' => $performance,

                'nombreProjets' => $souscriptions->count(),
            ],
        ]);
    }
}
