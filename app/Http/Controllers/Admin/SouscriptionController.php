<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SouscriptionController extends Controller
{
    public function index()
    {
        $souscriptions = Souscription::query()
            ->with('investisseur', 'projet', 'ticketInvestissement', 'paiements')
            ->latest('date_souscription')
            ->paginate(20);

        $statistiques = [
            'total' => Souscription::count(),
            'enAttente' => Souscription::where('statut', 'en_attente')->count(),
            'approuvees' => Souscription::where('statut', 'approuvée')->count(),
            'rejetees' => Souscription::where('statut', 'rejetée')->count(),
            'montantTotal' => Souscription::sum('montant_souscrit'),
            'montantCollecte' => Souscription::sum('montant_paye'),
        ];

        return Inertia::render('Admin/Souscriptions/Index', [
            'souscriptions' => $souscriptions,
            'statistiques' => $statistiques,
        ]);
    }

    public function show(Souscription $souscription)
    {
        $souscription->load(
            'investisseur',
            'projet',
            'ticketInvestissement',
            'paiements',
            'dividendes'
        );

        return Inertia::render('Admin/Souscriptions/Show', [
            'souscription' => $souscription,
            'paiements' => $souscription->paiements,
            'dividendes' => $souscription->dividendes,
        ]);
    }

    public function approuver(Souscription $souscription)
    {
        if ($souscription->statut !== 'en_attente') {
            return back()->with('error', 'Seules les souscriptions en attente peuvent être approuvées.');
        }

        // Vérifier que le KYC est approuvé
        if (!$souscription->investisseur->kyeApprouve()) {
            return back()->with('error', 'Le dossier KYC de cet investisseur n\'est pas approuvé.');
        }

        $souscription->approuver();

        // TODO: Envoyer notification d'approbation

        return back()->with('success', 'Souscription approuvée.');
    }

    public function rejeter(Souscription $souscription)
    {
        $validated = request()->validate([
            'raison' => 'required|string|max:500',
        ]);

        if ($souscription->statut !== 'en_attente') {
            return back()->with('error', 'Seules les souscriptions en attente peuvent être rejetées.');
        }

        $souscription->rejeter($validated['raison']);

        // Restaurer la disponibilité du ticket
        $ticket = $souscription->ticketInvestissement;
        $ticket->update([
            'montant_disponible' => $ticket->montant_disponible + $souscription->montant_souscrit,
            'nombre_places_disponibles' => $ticket->nombre_places_disponibles + 1,
        ]);

        // TODO: Envoyer notification de rejet

        return back()->with('success', 'Souscription rejetée.');
    }

    public function cancel(Souscription $souscription)
    {
        $validated = request()->validate([
            'raison' => 'required|string|max:500',
        ]);

        if ($souscription->statut !== 'approuvée') {
            return back()->with('error', 'Seules les souscriptions approuvées peuvent être annulées.');
        }

        // Rembourser les paiements complétés
        $paiementsCompletes = $souscription->paiements()
            ->where('statut', 'complété')
            ->get();

        foreach ($paiementsCompletes as $paiement) {
            $paiement->rembourser();
        }

        $souscription->update(['statut' => 'annulée']);

        return back()->with('success', 'Souscription annulée et paiements remboursés.');
    }

    public function generateContract(Souscription $souscription)
    {
        // TODO: Générer le PDF du contrat
        // Utiliser barryvdh/laravel-dompdf

        return response()->download('path/to/contract.pdf');
    }

    public function signContract(Souscription $souscription)
    {
        $validated = request()->validate([
            'signature_path' => 'required|string',
        ]);

        $souscription->update([
            'contrat_signe' => true,
            'date_signature' => now(),
            'chemin_contrat' => $validated['signature_path'],
        ]);

        return back()->with('success', 'Contrat signé.');
    }

    public function batch()
    {
        $validated = request()->validate([
            'action' => 'required|in:approuver,rejeter',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:souscriptions,id',
        ]);

        $souscriptions = Souscription::whereIn('id', $validated['ids'])->get();

        switch ($validated['action']) {
            case 'approuver':
                foreach ($souscriptions as $souscription) {
                    if ($souscription->statut === 'en_attente') {
                        $souscription->approuver();
                    }
                }
                return back()->with('success', count($souscriptions) . ' souscription(s) approuvée(s).');

            case 'rejeter':
                return back()->with('error', 'Utilisez l\'action individuelle pour rejeter.');
        }
    }

    public function export()
    {
        // Exporter les souscriptions (voir ExportController pour plus de détails)
        $souscriptions = Souscription::with('investisseur', 'projet', 'ticketInvestissement')
            ->get();

        return response()->json($souscriptions);
    }
}
