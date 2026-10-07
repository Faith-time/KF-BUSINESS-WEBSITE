<?php

namespace App\Models;

use App\Enums\StatutPaiement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'numero_paiement',
        'souscription_id',
        'investisseur_id',
        'methode_paiement_id',
        'montant',
        'frais',
        'montant_total',
        'statut',
        'date_paiement',
        'date_confirmation',
        'reference_externe',
        'raison_echec',
        'date_echec',
        'date_remboursement',
        'montant_devise_origine',
        'code_devise_origine',
        'taux_change',
        'notes',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'frais' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'taux_change' => 'decimal:4',
        'date_paiement' => 'date',
        'date_confirmation' => 'date',
        'date_echec' => 'datetime',
        'date_remboursement' => 'datetime',
        'statut' => StatutPaiement::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }

    public function investisseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investisseur_id');
    }

    public function methodePaiement(): BelongsTo
    {
        return $this->belongsTo(MethodePaiement::class, 'methode_paiement_id');
    }

    // Scopes
    public function scopeComplete($query)
    {
        return $query->where('statut', StatutPaiement::COMPLETED->value);
    }

    public function scopeEchoue($query)
    {
        return $query->where('statut', StatutPaiement::FAILED->value);
    }

    public function scopeEnTraitement($query)
    {
        return $query->where('statut', StatutPaiement::PROCESSING->value);
    }

    public function scopeParPeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_paiement', [$debut, $fin]);
    }

    // Methods
    public function confirmer($dateConfirmation = null)
    {
        $this->update([
            'statut' => StatutPaiement::COMPLETED,
            'date_confirmation' => $dateConfirmation ?? now(),
        ]);

        // Mettre à jour la souscription
        $souscription = $this->souscription;
        $souscription->update([
            'montant_paye' => $souscription->montant_paye + $this->montant,
        ]);
        $souscription->mettreAJourMontantRestant();

        // Marquer la souscription comme complétée si toute la souscription est payée
        if ($souscription->estCompletee()) {
            $souscription->update([
                'statut' => StatutSouscription::COMPLETED,
                'date_paiement_complet' => now(),
            ]);
        }

        // Mettre à jour le montant collecté du projet
        $projet = $souscription->projet;
        $projet->update([
            'montant_collecte' => $projet->souscriptions()->approuvee()->sum('montant_paye'),
        ]);
        $projet->mettreAJourPourcentageCompletion();
    }

    public function echec($raison)
    {
        $this->update([
            'statut' => StatutPaiement::FAILED,
            'raison_echec' => $raison,
            'date_echec' => now(),
        ]);
    }

    public function rembourser($dateRemboursement = null)
    {
        $this->update([
            'statut' => StatutPaiement::REFUNDED,
            'date_remboursement' => $dateRemboursement ?? now(),
        ]);

        // Mise à jour de la souscription
        $souscription = $this->souscription;
        $souscription->update([
            'montant_paye' => max(0, $souscription->montant_paye - $this->montant),
        ]);
        $souscription->mettreAJourMontantRestant();
    }

    public function genererNumeroPaiement()
    {
        if (!$this->numero_paiement) {
            $year = date('Y');
            $count = self::whereYear('created_at', $year)->count() + 1;
            $this->numero_paiement = 'PAY-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
        }
        return $this->numero_paiement;
    }

    public function montantNetInvestisseur(): float
    {
        return $this->montant - $this->frais;
    }
}
