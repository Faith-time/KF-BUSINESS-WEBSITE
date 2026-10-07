<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\TicketInvestissement;
use Inertia\Inertia;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = Projet::query()
            ->with('typeProjet', 'mediaProjet')
            ->where('visible', true)
            ->where('statut', 'financement')
            ->orderByDesc('featured')
            ->orderByDesc('created_at')
            ->paginate(12);

        return Inertia::render('Public/Projets/Index', [
            'projets' => $projets,
        ]);
    }

    public function show(Projet $projet)
    {
        // Vérifier que le projet est visible
        if (!$projet->visible) {
            abort(404);
        }

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
            'indicateurResultat',
        ]);

        $documentsPublics = $projet->documents()
            ->where('visible_public', true)
            ->where('date_publication', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_expiration')
                    ->orWhere('date_expiration', '>', now());
            })
            ->get();

        return Inertia::render('Public/Projets/Show', [
            'projet' => $projet,
            'tickets' => $projet->ticketInvestissement,
            'projections' => $projet->projectionFinanciere,
            'indicateurs' => $projet->indicateurResultat,
            'documents' => $documentsPublics,
            'nombreInvestisseurs' => $projet->souscriptions()
                ->where('statut', 'approuvée')
                ->distinct('investisseur_id')
                ->count(),
        ]);
    }

    public function search()
    {
        $query = request('q', '');
        $type = request('type');
        $secteur = request('secteur');

        $projets = Projet::query()
            ->with('typeProjet')
            ->where('visible', true)
            ->where('statut', 'financement');

        if ($query) {
            $projets->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($type) {
            $projets->where('type_projet_id', $type);
        }

        if ($secteur) {
            $projets->where('localisation', 'like', "%{$secteur}%");
        }

        $projets = $projets->paginate(12);

        return Inertia::render('Public/Projets/Search', [
            'projets' => $projets,
            'query' => $query,
            'type' => $type,
            'secteur' => $secteur,
        ]);
    }
}
