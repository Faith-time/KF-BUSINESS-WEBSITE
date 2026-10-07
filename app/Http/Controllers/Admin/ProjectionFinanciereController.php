<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\ProjectionFinanciere;
use Inertia\Inertia;

class ProjectionFinanciereController extends Controller
{
    public function index(Projet $projet)
    {
        $projections = $projet->projectionFinanciere()
            ->orderBy('date_projection')
            ->paginate(20);

        return Inertia::render('Admin/Projections/Index', [
            'projet' => $projet,
            'projections' => $projections,
        ]);
    }

    public function create(Projet $projet)
    {
        return Inertia::render('Admin/Projections/Create', [
            'projet' => $projet,
        ]);
    }

    public function store(Projet $projet)
    {
        $validated = request()->validate([
            'mois_projection' => 'required|integer|min:1|max:60',
            'date_projection' => 'required|date',
            'revenus_projetes' => 'required|numeric|min:0',
            'charges_projetes' => 'required|numeric|min:0',
            'flux_tresorerie' => 'required|numeric',
            'benefice_net_projete' => 'required|numeric',
            'taux_croissance' => 'nullable|numeric|min:-100|max:100',
            'assumptions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $projet->projectionFinanciere()->create($validated);

        return redirect()->route('admin.projets.projections.index', $projet)
            ->with('success', 'Projection financière créée.');
    }

    public function edit(Projet $projet, ProjectionFinanciere $projection)
    {
        if ($projection->projet_id !== $projet->id) {
            abort(404);
        }

        return Inertia::render('Admin/Projections/Edit', [
            'projet' => $projet,
            'projection' => $projection,
        ]);
    }

    public function update(Projet $projet, ProjectionFinanciere $projection)
    {
        if ($projection->projet_id !== $projet->id) {
            abort(404);
        }

        $validated = request()->validate([
            'mois_projection' => 'required|integer|min:1|max:60',
            'date_projection' => 'required|date',
            'revenus_projetes' => 'required|numeric|min:0',
            'charges_projetes' => 'required|numeric|min:0',
            'flux_tresorerie' => 'required|numeric',
            'benefice_net_projete' => 'required|numeric',
            'taux_croissance' => 'nullable|numeric|min:-100|max:100',
            'assumptions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $projection->update($validated);

        return back()->with('success', 'Projection mise à jour.');
    }

    public function show(Projet $projet, ProjectionFinanciere $projection)
    {
        if ($projection->projet_id !== $projet->id) {
            abort(404);
        }

        return Inertia::render('Admin/Projections/Show', [
            'projet' => $projet,
            'projection' => $projection,
            'statistiques' => [
                'margeNette' => $projection->margeNette(),
                'ratioCharge' => $projection->ratioCharge(),
            ],
        ]);
    }

    public function destroy(Projet $projet, ProjectionFinanciere $projection)
    {
        if ($projection->projet_id !== $projet->id) {
            abort(404);
        }

        $projection->delete();

        return back()->with('success', 'Projection supprimée.');
    }

    public function generateProjetions(Projet $projet)
    {
        $validated = request()->validate([
            'nombre_mois' => 'required|integer|min:1|max:60',
            'revenus_mensuels_base' => 'required|numeric|min:0',
            'taux_croissance_mensuel' => 'required|numeric|min:-50|max:100',
            'charges_mensuelles_base' => 'required|numeric|min:0',
            'taux_croissance_charges' => 'required|numeric|min:-50|max:100',
        ]);

        $projet->projectionFinanciere()->delete(); // Supprime les anciennes projections

        $dateDebut = $projet->date_debut;
        $revenusActuels = $validated['revenus_mensuels_base'];
        $chargesActuelles = $validated['charges_mensuelles_base'];

        for ($mois = 1; $mois <= $validated['nombre_mois']; $mois++) {
            $dateProjection = $dateDebut->addMonths($mois - 1);

            $revenusProjectes = $revenusActuels * pow(1 + $validated['taux_croissance_mensuel'] / 100, $mois - 1);
            $chargesProjectees = $chargesActuelles * pow(1 + $validated['taux_croissance_charges'] / 100, $mois - 1);

            $fluxTresorerie = $revenusProjectes - $chargesProjectees;
            $beneficeNet = $fluxTresorerie;

            $projet->projectionFinanciere()->create([
                'mois_projection' => $mois,
                'date_projection' => $dateProjection,
                'revenus_projetes' => $revenusProjectes,
                'charges_projetes' => $chargesProjectees,
                'flux_tresorerie' => $fluxTresorerie,
                'benefice_net_projete' => $beneficeNet,
                'taux_croissance' => $validated['taux_croissance_mensuel'],
                'assumptions' => 'Générées automatiquement',
            ]);
        }

        return back()->with('success', $validated['nombre_mois'] . ' mois de projections générés.');
    }
}
