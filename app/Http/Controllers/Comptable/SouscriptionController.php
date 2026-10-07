<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use Inertia\Inertia;

class SouscriptionController extends Controller
{
    public function index()
    {
        $souscriptions = Souscription::query()
            ->with('investisseur', 'projet', 'ticketInvestissement', 'paiements')
            ->latest('date_souscription')
            ->paginate(20);

        return Inertia::render('Comptable/Souscriptions/Index', [
            'souscriptions' => $souscriptions,
        ]);
    }

    public function show(Souscription $souscription)
    {
        $souscription->load('investisseur', 'projet', 'ticketInvestissement', 'paiements', 'dividendes');

        return Inertia::render('Comptable/Souscriptions/Show', [
            'souscription' => $souscription,
        ]);
    }
}
