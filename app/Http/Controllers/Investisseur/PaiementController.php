<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\Paiement;
use App\Models\MethodePaiement;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaiementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $paiements = $user->paiements()
            ->with('souscription.projet', 'methodePaiement')
            ->latest('date_paiement')
            ->paginate(10);

        return Inertia::render('Investisseur/Paiements/Index', [
            'paiements' => $paiements,
        ]);
    }

    public function create(Souscription $souscription)
    {
        if ($souscription->investisseur_id !== Auth::id()) {
            abort(403);
        }

        if ($souscription->montant_restant <= 0) {
            return back()->with('info', 'Le paiement de cette souscription est complet.');
        }

        $methodesPaiement = MethodePaiement::active()->get();

        return Inertia::render('Investisseur/Paiements/Create', [
            'souscription' => $souscription->load('projet'),
            'montantRestant' => $souscription->montant_restant,
            'methodesPaiement' => $methodesPaiement,
        ]);
    }

    public function store(Souscription $souscription)
    {
        if ($souscription->investisseur_id !== Auth::id()) {
            abort(403);
        }

        $validated = request()->validate([
            'montant' => 'required|numeric|min:1|max:' . $souscription->montant_restant,
            'methode_paiement_id' => 'required|exists:methode_paiements,id',
        ]);

        $methodePaiement = MethodePaiement::findOrFail($validated['methode_paiement_id']);
        $frais = $methodePaiement->calculerFrais($validated['montant']);

        $paiement = Paiement::create([
            'numero_paiement' => null,
            'souscription_id' => $souscription->id,
            'investisseur_id' => Auth::id(),
            'methode_paiement_id' => $methodePaiement->id,
            'montant' => $validated['montant'],
            'frais' => $frais,
            'montant_total' => $validated['montant'] + $frais,
            'statut' => 'en_attente',
            'date_paiement' => now(),
        ]);

        $paiement->genererNumeroPaiement();
        $paiement->save();

        // Rediriger vers la plateforme de paiement (Wave, MTN, etc.)
        // Ceci est un exemple simplifié
        return redirect()->route('investisseur.paiements.show', $paiement->id)
            ->with('success', 'Paiement initialisé. Veuillez confirmer via ' . $methodePaiement->nom);
    }

    public function show(Paiement $paiement)
    {
        if ($paiement->investisseur_id !== Auth::id()) {
            abort(403);
        }

        $paiement->load('souscription.projet', 'methodePaiement');

        return Inertia::render('Investisseur/Paiements/Show', [
            'paiement' => $paiement,
        ]);
    }
}
