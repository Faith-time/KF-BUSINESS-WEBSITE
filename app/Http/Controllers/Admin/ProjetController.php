<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\TypeProjet;
use App\Models\User;
use Inertia\Inertia;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = Projet::with('typeProjet', 'promoteur', 'mediaProjet')
            ->latest('created_at')
            ->paginate(20);

        return Inertia::render('Admin/Projets/Index', [
            'projets' => $projets,
        ]);
    }

    public function create()
    {
        $typeProjets = TypeProjet::all();
        $promoteurs = User::role('promoteur')->get();

        return Inertia::render('Admin/Projets/Create', [
            'typeProjets' => $typeProjets,
            'promoteurs' => $promoteurs,
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'nom' => 'required|string|unique:projets',
            'description' => 'required|string',
            'description_courte' => 'required|string|max:500',
            'type_projet_id' => 'required|exists:type_projets,id',
            'promoteur_id' => 'required|exists:users,id',
            'localisation' => 'required|string',
            'montant_total' => 'required|numeric|min:0',
            'montant_min_investissement' => 'required|numeric|min:0',
            'duree_mois' => 'required|integer|min:1',
            'taux_rendement_annuel' => 'required|numeric|min:0|max:100',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'date_fermeture_collecte' => 'required|date|before:date_debut',
        ]);

        $validated['slug'] = \Str::slug($validated['nom']);

        Projet::create($validated);

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet créé avec succès.');
    }

    public function show(Projet $projet)
    {
        $projet->load('typeProjet', 'promoteur', 'mediaProjet', 'souscriptions', 'paiements');

        return Inertia::render('Admin/Projets/Show', [
            'projet' => $projet,
        ]);
    }

    public function edit(Projet $projet)
    {
        $typeProjets = TypeProjet::all();
        $promoteurs = User::role('promoteur')->get();

        return Inertia::render('Admin/Projets/Edit', [
            'projet' => $projet,
            'typeProjets' => $typeProjets,
            'promoteurs' => $promoteurs,
        ]);
    }

    public function update(Projet $projet)
    {
        $validated = request()->validate([
            'nom' => 'required|string|unique:projets,nom,' . $projet->id,
            'description' => 'required|string',
            'description_courte' => 'required|string|max:500',
            'type_projet_id' => 'required|exists:type_projets,id',
            'promoteur_id' => 'required|exists:users,id',
            'localisation' => 'required|string',
            'montant_total' => 'required|numeric|min:0',
            'taux_rendement_annuel' => 'required|numeric|min:0|max:100',
            'statut' => 'required|in:préparation,financement,en_cours,terminé,suspendu,annulé',
            'featured' => 'boolean',
            'visible' => 'boolean',
        ]);

        $projet->update($validated);

        return back()->with('success', 'Projet mis à jour.');
    }

    public function destroy(Projet $projet)
    {
        $projet->delete();

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet supprimé.');
    }
}
