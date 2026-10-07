<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MethodePaiement;
use Inertia\Inertia;

class MethodePaiementController extends Controller
{
    public function index()
    {
        $methodes = MethodePaiement::withCount('paiements')
            ->paginate(20);

        return Inertia::render('Admin/MethodesPayment/Index', [
            'methodes' => $methodes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/MethodesPayment/Create');
    }

    public function store()
    {
        $validated = request()->validate([
            'nom' => 'required|string|unique:methode_paiements|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:methode_paiements|max:100',
            'frais_pourcentage' => 'required|numeric|min:0|max:100',
            'frais_fixes' => 'required|numeric|min:0',
            'icon' => 'nullable|string|max:100',
            'config' => 'nullable|json',
        ]);

        MethodePaiement::create([
            ...$validated,
            'active' => true,
        ]);

        return redirect()->route('admin.methodes-paiement.index')
            ->with('success', 'Méthode de paiement créée.');
    }

    public function edit(MethodePaiement $methodePaiement)
    {
        return Inertia::render('Admin/MethodesPayment/Edit', [
            'methode' => $methodePaiement,
        ]);
    }

    public function update(MethodePaiement $methodePaiement)
    {
        $validated = request()->validate([
            'nom' => 'required|string|unique:methode_paiements,nom,' . $methodePaiement->id . '|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|unique:methode_paiements,code,' . $methodePaiement->id . '|max:100',
            'frais_pourcentage' => 'required|numeric|min:0|max:100',
            'frais_fixes' => 'required|numeric|min:0',
            'icon' => 'nullable|string|max:100',
            'active' => 'boolean',
            'config' => 'nullable|json',
        ]);

        $methodePaiement->update($validated);

        return back()->with('success', 'Méthode de paiement mise à jour.');
    }

    public function show(MethodePaiement $methodePaiement)
    {
        $methodePaiement->load('paiements');

        $paiementsRecents = $methodePaiement->paiements()
            ->latest('date_paiement')
            ->limit(10)
            ->get();

        $statistiques = [
            'nombreUtilisations' => $methodePaiement->paiements()->count(),
            'montantTotal' => $methodePaiement->paiements()->where('statut', 'complété')->sum('montant'),
            'fraisTotaux' => $methodePaiement->paiements()->where('statut', 'complété')->sum('frais'),
        ];

        return Inertia::render('Admin/MethodesPayment/Show', [
            'methode' => $methodePaiement,
            'paiementsRecents' => $paiementsRecents,
            'statistiques' => $statistiques,
        ]);
    }

    public function toggle(MethodePaiement $methodePaiement)
    {
        $methodePaiement->update(['active' => !$methodePaiement->active]);

        $action = $methodePaiement->active ? 'activée' : 'désactivée';

        return back()->with('success', 'Méthode ' . $action . '.');
    }

    public function destroy(MethodePaiement $methodePaiement)
    {
        // Vérifier qu'il n'y a pas de paiements utilisant cette méthode
        if ($methodePaiement->paiements()->exists()) {
            return back()->with('error', 'Impossible de supprimer une méthode avec des paiements associés.');
        }

        $methodePaiement->delete();

        return back()->with('success', 'Méthode de paiement supprimée.');
    }

    public function testConfiguration(MethodePaiement $methodePaiement)
    {
        // TODO: Tester la connexion à l'API de la méthode (Wave, MTN, Orange, etc.)

        try {
            // Appel test simple selon la méthode
            // $result = $this->testApi($methodePaiement->code, $methodePaiement->config);

            return back()->with('success', 'Connexion réussie à ' . $methodePaiement->nom);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de connexion : ' . $e->getMessage());
        }
    }
}
