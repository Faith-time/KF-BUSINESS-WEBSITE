<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Souscription;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class InvestisseurController extends Controller
{
    public function index()
    {
        $investisseurs = User::query()
            ->role('investisseur')
            ->with('souscriptions', 'dossierKyc', 'profilInvestisseur')
            ->withCount(['souscriptions', 'paiements'])
            ->paginate(20);

        return Inertia::render('Admin/Investisseurs/Index', [
            'investisseurs' => $investisseurs,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Investisseurs/Create');
    }

    public function store()
    {
        $validated = request()->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'name' => $validated['prenom'] . ' ' . $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('investisseur');

        return redirect()->route('admin.investisseurs.show', $user)
            ->with('success', 'Investisseur créé avec succès.');
    }

    public function show(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        $investisseur->load(
            'souscriptions.projet',
            'souscriptions.paiements',
            'souscriptions.dividendes',
            'dossierKyc',
            'profilInvestisseur'
        );

        $souscriptionsApprouvees = $investisseur->souscriptions()
            ->where('statut', 'approuvée')
            ->get();

        $totalInvesti = $souscriptionsApprouvees->sum('montant_souscrit');
        $totalVerse = $souscriptionsApprouvees->sum('montant_paye');
        $totalDividendes = $investisseur->dividendes()->where('statut', 'versé')->sum('montant_net');

        return Inertia::render('Admin/Investisseurs/Show', [
            'investisseur' => $investisseur,
            'souscriptions' => $investisseur->souscriptions,
            'paiements' => $investisseur->paiements,
            'dossierKyc' => $investisseur->dossierKyc,
            'statistiques' => [
                'totalInvesti' => $totalInvesti,
                'totalVerse' => $totalVerse,
                'montantRestant' => $totalInvesti - $totalVerse,
                'totalDividendes' => $totalDividendes,
                'nombreSouscriptions' => $investisseur->souscriptions->count(),
                'kyceApprouve' => $investisseur->dossierKyc?->estApprouve(),
            ],
        ]);
    }

    public function edit(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        return Inertia::render('Admin/Investisseurs/Edit', [
            'investisseur' => $investisseur,
        ]);
    }

    public function update(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        $validated = request()->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $investisseur->id,
            'telephone' => 'required|string|max:20',
        ]);

        $validated['name'] = $validated['prenom'] . ' ' . $validated['nom'];

        $investisseur->update($validated);

        return back()->with('success', 'Investisseur mis à jour.');
    }

    public function deactivate(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        $investisseur->update(['email_verified_at' => null]); // Ou soft delete

        return back()->with('success', 'Investisseur désactivé.');
    }

    public function resetPassword(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        $newPassword = 'TempPassword' . random_int(1000, 9999);

        $investisseur->update(['password' => Hash::make($newPassword)]);

        // TODO: Envoyer email avec le nouveau mot de passe

        return back()->with('success', 'Mot de passe réinitialisé. Un email a été envoyé à ' . $investisseur->email);
    }

    public function destroy(User $investisseur)
    {
        if (!$investisseur->hasRole('investisseur')) {
            abort(404);
        }

        // Vérifier qu'il n'y a pas de souscriptions actives
        if ($investisseur->souscriptions()->whereIn('statut', ['en_attente', 'approuvée'])->exists()) {
            return back()->with('error', 'Impossible de supprimer : cet investisseur a des souscriptions actives.');
        }

        $investisseur->delete();

        return redirect()->route('admin.investisseurs.index')
            ->with('success', 'Investisseur supprimé.');
    }
}
