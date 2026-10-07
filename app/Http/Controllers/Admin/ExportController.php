<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\Dividende;
use App\Models\Projet;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class ExportController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Exports/Index');
    }

    // ============ EXPORTS EXCEL ============

    public function exportPaiements()
    {
        $validated = request()->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'nullable|in:en_attente,traitement,complété,échoué,remboursé,annulé',
        ]);

        $query = Paiement::with('souscription.investisseur', 'souscription.projet', 'methodePaiement');

        if ($validated['date_debut'] && $validated['date_fin']) {
            $query->whereBetween('date_paiement', [$validated['date_debut'], $validated['date_fin']]);
        }

        if ($validated['statut']) {
            $query->where('statut', $validated['statut']);
        }

        $paiements = $query->get();

        return Excel::download(new \App\Exports\PaiementsExport($paiements), 'paiements.xlsx');
    }

    public function exportSouscriptions()
    {
        $validated = request()->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'nullable|in:en_attente,approuvée,rejetée,annulée,terminée',
        ]);

        $query = Souscription::with('investisseur', 'projet', 'ticketInvestissement', 'paiements');

        if ($validated['date_debut'] && $validated['date_fin']) {
            $query->whereBetween('date_souscription', [$validated['date_debut'], $validated['date_fin']]);
        }

        if ($validated['statut']) {
            $query->where('statut', $validated['statut']);
        }

        $souscriptions = $query->get();

        return Excel::download(new \App\Exports\SouscriptionsExport($souscriptions), 'souscriptions.xlsx');
    }

    public function exportDividendes()
    {
        $validated = request()->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'statut' => 'nullable|in:planifié,approuvé,versé,reporté',
        ]);

        $query = Dividende::with('souscription.investisseur', 'projet', 'souscription.projet');

        if ($validated['date_debut'] && $validated['date_fin']) {
            $query->whereBetween('date_periode_debut', [$validated['date_debut'], $validated['date_fin']]);
        }

        if ($validated['statut']) {
            $query->where('statut', $validated['statut']);
        }

        $dividendes = $query->get();

        return Excel::download(new \App\Exports\DividendesExport($dividendes), 'dividendes.xlsx');
    }

    public function exportInvestisseurs()
    {
        $investisseurs = User::role('investisseur')
            ->with('souscriptions', 'dossierKyc', 'profilInvestisseur')
            ->get();

        return Excel::download(new \App\Exports\InvestisseursExport($investisseurs), 'investisseurs.xlsx');
    }

    public function exportProjets()
    {
        $projets = Projet::with('typeProjet', 'promoteur', 'souscriptions')
            ->get();

        return Excel::download(new \App\Exports\ProjetsExport($projets), 'projets.xlsx');
    }

    // ============ EXPORTS PDF ============

    public function exportSouscriptionsPDF()
    {
        $validated = request()->validate([
            'souscription_id' => 'required|exists:souscriptions,id',
        ]);

        $souscription = Souscription::with('investisseur', 'projet', 'ticketInvestissement')
            ->findOrFail($validated['souscription_id']);

        $pdf = Pdf::loadView('exports.contrat-souscription', [
            'souscription' => $souscription,
        ]);

        return $pdf->download('contrat-' . $souscription->numero_souscription . '.pdf');
    }

    public function exportAttestation(Souscription $souscription)
    {
        $pdf = Pdf::loadView('exports.attestation-investisseur', [
            'souscription' => $souscription,
        ]);

        return $pdf->download('attestation-' . $souscription->numero_souscription . '.pdf');
    }

    public function exportRapportFinancier(Projet $projet)
    {
        $projet->load('souscriptions', 'paiements', 'dividendes', 'projectionFinanciere');

        $pdf = Pdf::loadView('exports.rapport-financier', [
            'projet' => $projet,
        ]);

        return $pdf->download('rapport-financier-' . $projet->slug . '.pdf');
    }

    public function exportRecapitulatif(Souscription $souscription)
    {
        $souscription->load('investisseur', 'projet', 'paiements', 'dividendes');

        $pdf = Pdf::loadView('exports.recapitulatif-souscription', [
            'souscription' => $souscription,
        ]);

        return $pdf->download('recapitulatif-' . $souscription->numero_souscription . '.pdf');
    }

    // ============ RAPPORTS STATISTIQUES ============

    public function rapportTresorerie()
    {
        $validated = request()->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $paiements = Paiement::whereBetween('date_paiement', [$validated['date_debut'], $validated['date_fin']])
            ->where('statut', 'complété')
            ->get();

        $montantTotal = $paiements->sum('montant');
        $fraisTotal = $paiements->sum('frais');
        $montantNet = $montantTotal - $fraisTotal;

        $pdf = Pdf::loadView('exports.rapport-tresorerie', [
            'paiements' => $paiements,
            'montantTotal' => $montantTotal,
            'fraisTotal' => $fraisTotal,
            'montantNet' => $montantNet,
            'datDebut' => $validated['date_debut'],
            'dateFin' => $validated['date_fin'],
        ]);

        return $pdf->download('rapport-tresorerie.pdf');
    }

    public function rapportDividendes()
    {
        $validated = request()->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $dividendes = Dividende::whereBetween('date_periode_debut', [$validated['date_debut'], $validated['date_fin']])
            ->where('statut', 'versé')
            ->with('souscription.investisseur', 'projet')
            ->get();

        $montantBrut = $dividendes->sum('montant_brut');
        $retenuSource = $dividendes->sum('retenue_source');
        $montantNet = $dividendes->sum('montant_net');

        $pdf = Pdf::loadView('exports.rapport-dividendes', [
            'dividendes' => $dividendes,
            'montantBrut' => $montantBrut,
            'retenuSource' => $retenuSource,
            'montantNet' => $montantNet,
            'dateDebut' => $validated['date_debut'],
            'dateFin' => $validated['date_fin'],
        ]);

        return $pdf->download('rapport-dividendes.pdf');
    }

    public function rapportActivite()
    {
        $validated = request()->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $data = [
            'nouvellesInscriptions' => User::role('investisseur')
                ->whereBetween('created_at', [$validated['date_debut'], $validated['date_fin']])
                ->count(),
            'nouvellesSouscriptions' => Souscription::whereBetween('date_souscription', [$validated['date_debut'], $validated['date_fin']])
                ->count(),
            'paiementsRecus' => Paiement::whereBetween('date_paiement', [$validated['date_debut'], $validated['date_fin']])
                ->where('statut', 'complété')
                ->sum('montant'),
            'dividendesDistribues' => Dividende::whereBetween('date_paiement_reel', [$validated['date_debut'], $validated['date_fin']])
                ->where('statut', 'versé')
                ->sum('montant_net'),
        ];

        $pdf = Pdf::loadView('exports.rapport-activite', [
            'data' => $data,
            'dateDebut' => $validated['date_debut'],
            'dateFin' => $validated['date_fin'],
        ]);

        return $pdf->download('rapport-activite.pdf');
    }

    public function configurationExports()
    {
        return Inertia::render('Admin/Exports/Configuration', [
            'templates' => [
                'contrat_souscription',
                'attestation_investisseur',
                'rapport_financier',
                'recapitulatif_souscription',
                'rapport_tresorerie',
                'rapport_dividendes',
                'rapport_activite',
            ],
        ]);
    }
}
