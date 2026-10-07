<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Inertia\Inertia;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::query()
            ->with('souscription.investisseur', 'souscription.projet', 'methodePaiement')
            ->latest('date_paiement')
            ->paginate(20);

        return Inertia::render('Comptable/Paiements/Index', [
            'paiements' => $paiements,
        ]);
    }

    public function confirmer(Paiement $paiement)
    {
        if ($paiement->statut !== 'en_attente') {
            return back()->with('error', 'Ce paiement a déjà été traité.');
        }

        $paiement->confirmer();

        return back()->with('success', 'Paiement confirmé.');
    }


    public function rejeter(Paiement $paiement)
    {
        $raison = request('raison');

        if (!$raison) {
            return back()->with('error', 'Veuillez fournir une raison.');
        }

        $paiement->echec($raison);

        return back()->with('success', 'Paiement rejeté.');
    }
}
