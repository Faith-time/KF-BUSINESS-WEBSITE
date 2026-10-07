<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class InvestisseurController extends Controller
{
    public function index()
    {
        $investisseurs = User::query()
            ->role('investisseur')
            ->with('souscriptions', 'dossierKyc')
            ->paginate(20);

        return Inertia::render('Comptable/Investisseurs/Index', [
            'investisseurs' => $investisseurs,
        ]);
    }

    public function show(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        $investisseur->load('souscriptions.projet', 'paiements', 'dossierKyc');

        $totalInvesti = $investisseur->souscriptions()
            ->where('statut', 'approuvée')

            ->sum('montant_souscrit');

        return Inertia::render('Comptable/Investisseurs/Show', [
            'investisseur' => $investisseur,
            'statistiques' => [
                'totalInvesti' => $totalInvesti,
                'nombreSouscriptions' => $investisseur->souscriptions->count(),
            ],
        ]);
    }
}
