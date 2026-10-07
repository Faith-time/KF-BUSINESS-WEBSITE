<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\TicketInvestissement;
use Inertia\Inertia;

class TicketInvestissementController extends Controller
{
    public function index(Projet $projet)
    {
        $tickets = $projet->ticketInvestissement()
            ->with('souscriptions')
            ->paginate(20);

        return Inertia::render('Admin/Tickets/Index', [
            'projet' => $projet,
            'tickets' => $tickets,
        ]);
    }

    public function create(Projet $projet)
    {
        return Inertia::render('Admin/Tickets/Create', [
            'projet' => $projet,
        ]);
    }

    public function store(Projet $projet)
    {
        $validated = request()->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'montant_min' => 'required|numeric|min:0',
            'montant_max' => 'required|numeric|gt:montant_min',
            'nombre_places_max' => 'required|integer|min:1',
            'taux_rendement' => 'required|numeric|min:0|max:100',
            'type_rendement' => 'required|in:mensuel,trimestriel,annuel,à_maturité',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'delai_remboursement_mois' => 'required|integer|min:1',
            'conditions' => 'nullable|string',
        ]);

        $ticket = $projet->ticketInvestissement()->create([
            ...$validated,
            'montant_disponible' => $validated['montant_max'],
            'nombre_places_disponibles' => $validated['nombre_places_max'],
            'actif' => true,
        ]);

        return redirect()->route('admin.projets.tickets.index', $projet)
            ->with('success', 'Ticket d\'investissement créé.');
    }

    public function edit(Projet $projet, TicketInvestissement $ticket)
    {
        if ($ticket->projet_id !== $projet->id) {
            abort(404);
        }

        return Inertia::render('Admin/Tickets/Edit', [
            'projet' => $projet,
            'ticket' => $ticket,
        ]);
    }

    public function update(Projet $projet, TicketInvestissement $ticket)
    {
        if ($ticket->projet_id !== $projet->id) {
            abort(404);
        }

        $validated = request()->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'montant_min' => 'required|numeric|min:0',
            'montant_max' => 'required|numeric|gt:montant_min',
            'taux_rendement' => 'required|numeric|min:0|max:100',
            'type_rendement' => 'required|in:mensuel,trimestriel,annuel,à_maturité',
            'date_fin' => 'required|date|after:date_debut',
            'delai_remboursement_mois' => 'required|integer|min:1',
            'conditions' => 'nullable|string',
            'actif' => 'boolean',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Ticket mis à jour.');
    }

    public function show(Projet $projet, TicketInvestissement $ticket)
    {
        if ($ticket->projet_id !== $projet->id) {
            abort(404);
        }

        $ticket->load('souscriptions.investisseur', 'souscriptions.paiements');

        return Inertia::render('Admin/Tickets/Show', [
            'projet' => $projet,
            'ticket' => $ticket,
            'souscriptions' => $ticket->souscriptions,
            'statistiques' => [
                'montantUtilise' => $ticket->montantUtilise(),
                'placesUtilisees' => $ticket->placesUtilisees(),
                'pourcentageMontant' => $ticket->pourcentageCompletionMontant(),
                'pourcentagePlaces' => $ticket->pourcentageCompletionPlaces(),
            ],
        ]);
    }

    public function destroy(Projet $projet, TicketInvestissement $ticket)
    {
        if ($ticket->projet_id !== $projet->id) {
            abort(404);
        }

        // Vérifier qu'il n'y a pas de souscriptions actives
        if ($ticket->souscriptions()->where('statut', '!=', 'annulée')->exists()) {
            return back()->with('error', 'Impossible de supprimer un ticket avec des souscriptions actives.');
        }

        $ticket->delete();

        return back()->with('success', 'Ticket supprimé.');
    }
}
