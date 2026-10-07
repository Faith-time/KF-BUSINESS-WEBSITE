<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjetController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projets = Projet::query()
            ->with('typeProjet', 'mediaProjet')
            ->where('visible', true)
            ->where('statut', 'financement')
            ->paginate(12);

        $souscriptionsInvestisseur = $user->souscriptions()
            ->pluck('projet_id')
            ->toArray();

        return Inertia::render('Investisseur/Projets/Index', [
            'projets' => $projets,
            'souscriptionsInvestisseur' => $souscriptionsInvestisseur,
        ]);
    }

    public function show(Projet $projet)

    {
        $projet->load([
            'typeProjet',
            'promoteur',
            'mediaProjet',
            'ticketInvestissement' => function ($query) {
                $query->where('actif', true);
            },
            'projectionFinanciere' => function ($query) {
                $query->orderBy('date_projection')->limit(12);
            },
        ]);

        $user = Auth::user();
        $souscriptionInvestisseur = $user->souscriptions()
            ->where('projet_id', $projet->id)
            ->with('paiements')
            ->first();

        return Inertia::render('Investisseur/Projets/Show', [
            'projet' => $projet,
            'tickets' => $projet->ticketInvestissement,
            'souscription' => $souscriptionInvestisseur,
            'nombreInvestisseurs' => $projet->souscriptions()
                ->where('statut', 'approuvée')
                ->distinct('investisseur_id')
                ->count(),
        ]);
    }
}
