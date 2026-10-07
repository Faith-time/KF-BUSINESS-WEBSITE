<?php

namespace App\Http\Controllers\Investisseur;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\TicketInvestissement;
use App\Models\Projet;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SouscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $souscriptions = $user->souscriptions()
            ->with('projet', 'ticketInvestissement', 'paiements', 'dividendes')
            ->latest('date_souscription')
            ->paginate(10);

        return Inertia::render('Investisseur/Souscriptions/Index', [
            'souscriptions' => $souscriptions,
        ]);
    }

    public function create(Projet $projet, TicketInvestissement $ticket)
    {
        $user = Auth::user();

        // Vérifier le KYC

        if (!$user->kyeApprouve()) {
            return back()->with('error', 'Votre dossier KYC doit être approuvé pour souscrire.');
        }

        // Vérifier qu'une souscription n'existe pas déjà
        $existante = $user->souscriptions()
            ->where('projet_id', $projet->id)
            ->where('ticket_investissement_id', $ticket->id)
            ->exists();

        if ($existante) {
            return back()->with('error', 'Vous avez déjà une souscription pour ce ticket.');
        }

        return Inertia::render('Investisseur/Souscriptions/Create', [
            'projet' => $projet->load('mediaProjet'),
            'ticket' => $ticket,
            'montantMin' => $ticket->montant_min,
            'montantMax' => min($ticket->montant_max, $ticket->montant_disponible),
        ]);
    }

    public function store(Projet $projet, TicketInvestissement $ticket)
    {
        $user = Auth::user();
        $validated = request()->validate([
            'montant_souscrit' => 'required|numeric|min:' . $ticket->montant_min . '|max:' . $ticket->montant_disponible,
        ]);

        // Créer la souscription
        $souscription = Souscription::create([
            'numero_souscription' => null,

            'investisseur_id' => $user->id,
            'projet_id' => $projet->id,
            'ticket_investissement_id' => $ticket->id,
            'montant_souscrit' => $validated['montant_souscrit'],
            'montant_paye' => 0,
            'montant_restant' => $validated['montant_souscrit'],
            'nombre_parts' => intval($validated['montant_souscrit'] / $ticket->prix_par_part),
            'prix_par_part' => $ticket->prix_par_part,
            'statut' => 'en_attente',
            'date_souscription' => now(),
        ]);

        $souscription->genererNumeroSouscription();
        $souscription->save();

        // Mettre à jour le ticket
        $ticket->update([
            'montant_disponible' => $ticket->montant_disponible - $validated['montant_souscrit'],
            'nombre_places_disponibles' => $ticket->nombre_places_disponibles - 1,
        ]);

        return redirect()->route('investisseur.souscriptions.show', $souscription->id)
            ->with('success', 'Souscription créée avec succès. En attente d\'approbation.');
    }

    public function show(Souscription $souscription)
    {
        // Vérifier que c'est la souscription de l'utilisateur
        if ($souscription->investisseur_id !== Auth::id()) {
            abort(403);
        }


        $souscription->load('projet', 'ticketInvestissement', 'paiements', 'dividendes');

        return Inertia::render('Investisseur/Souscriptions/Show', [
            'souscription' => $souscription,
            'paiements' => $souscription->paiements,
            'dividendes' => $souscription->dividendes,
        ]);
    }

    public function cancel(Souscription $souscription)
    {
        if ($souscription->investisseur_id !== Auth::id()) {
            abort(403);
        }

        if ($souscription->statut !== 'en_attente') {
            return back()->with('error', 'Seules les souscriptions en attente peuvent être annulées.');
        }

        $souscription->update(['statut' => 'annulée']);

        // Restaurer le ticket
        $ticket = $souscription->ticketInvestissement;
        $ticket->update([
            'montant_disponible' => $ticket->montant_disponible + $souscription->montant_souscrit,
            'nombre_places_disponibles' => $ticket->nombre_places_disponibles + 1,
        ]);

        return back()->with('success', 'Souscription annulée.');
    }
}
