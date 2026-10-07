<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeProjet;
use Inertia\Inertia;

class TypeProjetController extends Controller
{
    public function index()
    {
        $types = TypeProjet::paginate(20);

        return Inertia::render('Admin/TypeProjets/Index', [
            'types' => $types,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/TypeProjets/Create');
    }

    public function store()
    {
        $validated = request()->validate([
            'nom' => 'required|string|unique:type_projets',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);


        TypeProjet::create($validated);

        return redirect()->route('admin.type-projets.index')
            ->with('success', 'Type créé avec succès.');
    }

    public function edit(TypeProjet $typeProjet)
    {
        return Inertia::render('Admin/TypeProjets/Edit', [
            'type' => $typeProjet,
        ]);
    }

    public function update(TypeProjet $typeProjet)
    {
        $validated = request()->validate([
            'nom' => 'required|string|unique:type_projets,nom,' . $typeProjet->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $typeProjet->update($validated);

        return back()->with('success', 'Type mis à jour.');
    }

    public function destroy(TypeProjet $typeProjet)
    {
        $typeProjet->delete();

        return back()->with('success', 'Type supprimé.');

    }
}
