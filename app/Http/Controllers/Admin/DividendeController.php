<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dividende;
use App\Models\Projet;
use App\Models\Souscription;
use Inertia\Inertia;

class DividendeController extends Controller
{
    public function index()
    {
        $dividendes = Dividende::query()
            ->with('souscription.investisseur', 'projet', 'souscription.projet')
            ->latest('date_periode_debut')
            ->paginate(20);

        $statistiques = [
            'total' => Dividende::count(),
            'planifies' => Dividende::where('statut', 'planifié')->count(),
            'approuves' => Dividende::where('statut', 'approuvé')->count(),
            'verses' => Dividende::where('statut', 'versé')->count(),
            'montantBrutTotal' => Dividende::sum('montant_brut'),
            'montantNetTotal' => Dividende::where('statut', 'versé')->sum('montant_net'),
            'retenueTotalSource' => Dividende::sum('retenue_source'),
        ];

        return Inertia::render('Admin/Dividendes/Index', [
            'dividendes' => $dividendes,
            'statistiques' => $statistiques,
        ]);
    }

    public function show(Dividende $dividende)
    {
        $dividende->load('souscription.investisseur', 'souscription.projet', 'projet');

        return Inertia::render('Admin/Dividendes/Show', [
            'dividende' => $dividende,
            'statistiques' => [
                'tauxImposition' => $dividende->tauxImposition(),
                'pourcentageVersement' => $dividende->pourcentageVersement(),
            ],
        ]);
    }

    public function generateDividendsForProject(Projet $projet)
    {
        $validated = request()->validate([
            'montant_total' => 'required|numeric|min:0',
            'numero_periode' => 'required|integer|min:1',
            'date_periode_debut' => 'required|date',
            'date_periode_fin' => 'required|date|after:date_periode_debut',
            'taux_rendement' => 'required|numeric|min:0|max:100',
            'type_rendement' => 'required|in:mensuel,trimestriel,annuel,à_maturité',
            'date_paiement_prevu' => 'required|date|after:date_periode_fin',
            'taux_retenue_source' => 'required|numeric|min:0|max:100',
        ]);

        // Récupérer toutes les souscriptions approuvées du projet
        $souscriptions = $projet->souscriptions()
            ->where('statut', 'approuvée')
            ->get();

        $nombreDividendes = 0;

        foreach ($souscriptions as $souscription) {
            // Calculer le dividende basé sur le montant souscrit
            $montantBrut = ($souscription->montant_souscrit / $projet->montant_total) * $validated['montant_total'];
            $retenuSource = $montantBrut * ($validated['taux_retenue_source'] / 100);
            $montantNet = $montantBrut - $retenuSource;

            Dividende::create([
                'souscription_id' => $souscription->id,
                'projet_id' => $projet->id,
                'numero_dividende' => null,
                'numero_periode' => $validated['numero_periode'],
                'date_periode_debut' => $validated['date_periode_debut'],
                'date_periode_fin' => $validated['date_periode_fin'],
                'montant_brut' => $montantBrut,
                'taux_dividende' => $validated['taux_rendement'],
                'retenue_source' => $retenuSource,
                'montant_net' => $montantNet,
                'statut' => 'planifié',
                'date_paiement_prevu' => $validated['date_paiement_prevu'],
                'methode_versement' => null,
            ]);

            $nombreDividendes++;
        }

        // Générer les numéros
        Dividende::where('numero_dividende', null)
            ->latest('id')
            ->limit($nombreDividendes)
            ->each(function ($dividende, $index) {
                $dividende->genererNumeroDividende();
                $dividende->save();
            });

        return back()->with('success', $nombreDividendes . ' dividende(s) planifié(s).');
    }

    public function approuver(Dividende $dividende)
    {
        if ($dividende->statut !== 'planifié') {
            return back()->with('error', 'Seuls les dividendes planifiés peuvent être approuvés.');
        }

        $dividende->approuver();

        return back()->with('success', 'Dividende approuvé.');
    }

    public function verser(Dividende $dividende)
    {
        if ($dividende->statut !== 'approuvé') {
            return back()->with('error', 'Seuls les dividendes approuvés peuvent être versés.');
        }

        $validated = request()->validate([
            'methode_versement' => 'required|string|max:100',
            'date_paiement' => 'nullable|date',
        ]);

        $dividende->update([
            'methode_versement' => $validated['methode_versement'],
        ]);

        $dividende->verser($validated['date_paiement'] ?? null);

        // TODO: Envoyer notification à l'investisseur

        return back()->with('success', 'Dividende versé à ' . $dividende->souscription->investisseur->email);
    }

    public function reporter(Dividende $dividende)
    {
        if (!in_array($dividende->statut, ['planifié', 'approuvé'])) {
            return back()->with('error', 'Seuls les dividendes planifiés ou approuvés peuvent être reportés.');
        }

        $validated = request()->validate([
            'nouvelle_date_paiement' => 'required|date|after:' . $dividende->date_paiement_prevu,
            'raison' => 'required|string|max:500',
        ]);

        $dividende->update([
            'date_paiement_prevu' => $validated['nouvelle_date_paiement'],
            'notes' => ($dividende->notes ?? '') . '\n[Reporté] ' . $validated['raison'],
        ]);

        return back()->with('success', 'Dividende reporté au ' . $validated['nouvelle_date_paiement']);
    }

    public function batch()
    {
        $validated = request()->validate([
            'action' => 'required|in:approuver,verser,reporter',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:dividendes,id',
        ]);

        $dividendes = Dividende::whereIn('id', $validated['ids'])->get();

        switch ($validated['action']) {
            case 'approuver':
                foreach ($dividendes as $dividende) {
                    if ($dividende->statut === 'planifié') {
                        $dividende->approuver();
                    }
                }
                return back()->with('success', count($dividendes) . ' dividende(s) approuvé(s).');

            case 'verser':
                return back()->with('error', 'Utilisez l\'action individuelle pour verser.');

            case 'reporter':
                return back()->with('error', 'Utilisez l\'action individuelle pour reporter.');
        }
    }

    public function filterByProject(Projet $projet)
    {
        $dividendes = $projet->dividendes()
            ->with('souscription.investisseur')
            ->latest('date_periode_debut')
            ->paginate(20);

        return response()->json($dividendes);
    }

    public function filterByStatus()
    {
        $status = request('status');

        $dividendes = Dividende::where('statut', $status)
            ->with('souscription.investisseur', 'projet')
            ->latest('date_periode_debut')
            ->paginate(20);

        return response()->json($dividendes);
    }
}
