<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use App\Models\ProfilInvestisseur;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('profilInvestisseur');

        return Inertia::render('Investisseur/Profil/Edit', [
            'user' => $user,
            'profil' => $user->profilInvestisseur,
        ]);
    }

    public function update()
    {
        $user = Auth::user();

        $validated = request()->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'type_investisseur' => 'required|in:individu,entreprise,institutionnel',
            'montant_min_investissement' => 'nullable|numeric|min:0',
            'montant_max_investissement' => 'nullable|numeric|min:0',

            'secteur_interet' => 'nullable|string|max:255',
            'risque_preference' => 'required|in:conservateur,modéré,agressif',
            'experience_investissement' => 'nullable|string',
            'localisation' => 'nullable|string|max:255',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
        ]);

        $user->update([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'telephone' => $validated['telephone'],
        ]);

        ProfilInvestisseur::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, ['user_id' => $user->id])
        );

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
