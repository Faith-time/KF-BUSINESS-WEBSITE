<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\Dividende;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function paiements()
    {
        $paiements = Paiement::with('souscription.investisseur', 'souscription.projet')
            ->get();

        return Excel::download(new PaiementsExport($paiements), 'paiements.xlsx');
    }

    public function souscriptions()
    {
        $souscriptions = Souscription::with('investisseur', 'projet')
            ->get();

        return Excel::download(new SouscriptionsExport($souscriptions), 'souscriptions.xlsx');
    }

    public function dividendes()
    {
        $dividendes = Dividende::with('souscription.investisseur', 'projet')
            ->get();


        return Excel::download(new DividendesExport($dividendes), 'dividendes.xlsx');
    }
}
