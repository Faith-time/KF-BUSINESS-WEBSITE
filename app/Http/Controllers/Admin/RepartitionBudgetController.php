<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\RepartitionBudget;
use Inertia\Inertia;

class RepartitionBudgetController extends Controller
{
    public function index(Projet $projet)
    {
        $budgets = $projet->repartitionBudget()
            ->paginate(20);

        $totalPrevu = $budgets->sum('montant_prevu');
        $totalReel = $budgets->sum('montant_reel');

        return Inertia::render('Admin/Budgets/Index', [
            'projet' => $projet,
            'budgets' => $budgets,
            'statistiques' => [
                'totalPrevu' => $totalPrevu,
                'totalReel' => $totalReel,
                'difference' => $totalReel - $totalPrevu,
                'pourcentage' => $totalPrevu > 0 ? ($totalReel / $totalPrevu) * 100 : 0,
            ],
        ]);
    }

    public function create(Projet $projet)
    {
        return Inertia::render('Admin/Budgets/Create', [
            'projet' => $projet,
        ]);
    }

    public function store(Projet $projet)
    {
        $validated = request()->validate([
            'categorie' => 'required|string|max:255',
            'montant_prevu' => 'required|numeric|min:0',
            'description' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $budget = $projet->repartitionBudget()->create([
            ...$validated,
            'montant_reel' => 0,
            'statut' => 'planifié',
            'pourcentage_completion' => 0,
        ]);

        return redirect()->route('admin.projets.budgets.index', $projet)
            ->with('success', 'Catégorie budgétaire créée.');
    }

    public function edit(Projet $projet, RepartitionBudget $budget)
    {
        if ($budget->projet_id !== $projet->id) {
            abort(404);
        }

        return Inertia::render('Admin/Budgets/Edit', [
            'projet' => $projet,
            'budget' => $budget,
        ]);
    }

    public function update(Projet $projet, RepartitionBudget $budget)
    {
        if ($budget->projet_id !== $projet->id) {
            abort(404);
        }

        $validated = request()->validate([
            'categorie' => 'required|string|max:255',
            'montant_prevu' => 'required|numeric|min:0',
            'montant_reel' => 'required|numeric|min:0',
            'description' => 'required|string',
            'statut' => 'required|in:planifié,en_cours,terminé,dépassé',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);
        $budget->mettreAJourCompletion();

        return back()->with('success', 'Catégorie budgétaire mise à jour.');
    }

    public function show(Projet $projet, RepartitionBudget $budget)
    {
        if ($budget->projet_id !== $projet->id) {
            abort(404);
        }

        return Inertia::render('Admin/Budgets/Show', [
            'projet' => $projet,
            'budget' => $budget,
            'statistiques' => [
                'difference' => $budget->difference(),
                'estDeplasse' => $budget->estDeplasse(),
                'avancement' => $budget->pourcentage_completion,
            ],
        ]);
    }

    public function destroy(Projet $projet, RepartitionBudget $budget)
    {
        if ($budget->projet_id !== $projet->id) {
            abort(404);
        }

        $budget->delete();

        return back()->with('success', 'Catégorie budgétaire supprimée.');
    }
}
