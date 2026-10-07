<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use App\Models\DossierKyc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DossierKycController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $dossierKyc = $user->dossierKyc ?? new DossierKyc();

        return Inertia::render('Investisseur/DossierKYC/Show', [
            'dossier' => $dossierKyc,
            'user' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        $dossierKyc = $user->dossierKyc ?? new DossierKyc();

        return Inertia::render('Investisseur/DossierKYC/Edit', [
            'dossier' => $dossierKyc,
            'user' => $user,
        ]);

    }

    public function submit()
    {
        $user = Auth::user();

        $validated = request()->validate([
            'type_identification' => 'required|string',
            'numero_identification' => 'required|string',
            'date_delivrance' => 'required|date',
            'date_expiration' => 'required|date|after:date_delivrance',
            'adresse_complete' => 'required|string',
            'ville' => 'required|string',
            'pays' => 'required|string',
            'telephone_verification' => 'required|string',
            'numero_compte_bancaire' => 'required|string',
            'nom_banque' => 'required|string',
            'sources_fonds' => 'required|string',
            'activite_professionnelle' => 'required|string',
            'secteur_activite' => 'required|string',
        ]);

        $dossier = DossierKyc::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, [
                'user_id' => $user->id,
                'statut' => 'soumis',
            ])
        );

        return redirect()->route('investisseur.dossier-kyc.show')
            ->with('success', 'Dossier KYC soumis. Verification en cours.');

    }
}
