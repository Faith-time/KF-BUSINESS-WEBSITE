<?php

namespace App\Models;

use App\Enums\StatutSouscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Souscription extends Model
{
    protected $table = 'souscriptions';

    protected $fillable = [
        'numero_souscription',
        'investisseur_id',
        'projet_id',
        'ticket_investissement_id',
        'montant_souscrit',
        'montant_paye',
        'montant_restant',
        'nombre_parts',
        'prix_par_part',
        'statut',
        'date_souscription',
        'date_approbation',
        'date_paiement_complet',
        'raison_rejet',
        'date_rejet',
        'contrat_signe',
        'date_signature',
        'chemin_contrat',
        'conditions_additionnelles',
        'frais_traitement',
        'notes',
    ];

    protected $casts = [
        'montant_souscrit' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'prix_par_part' => 'decimal:2',
        'frais_traitement' => 'decimal:2',
        'date_souscription' => 'date',
        'date_approbation' => 'date',
        'date_paiement_complet' => 'date',
        'date_rejet' => 'datetime',
        'contrat_signe' => 'boolean',
        'date_signature' => 'datetime',
        'statut' => StatutSouscription::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function investisseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investisseur_id');
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    public function ticketInvestissement(): BelongsTo
    {
        return $this->belongsTo(TicketInvestissement::class, 'ticket_investissement_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'souscription_id');
    }

    public function dividendes(): HasMany
    {
        return $this->hasMany(Dividende::class, 'souscription_id');
    }

    // Scopes
    public function scopeApprouvee($query)
    {
        return $query->where('statut', StatutSouscription::APPROVED->value);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', StatutSouscription::PENDING->value);
    }

    public function scopeTerminee($query)
    {
        return $query->where('statut', StatutSouscription::COMPLETED->value);
    }

    public function scopeParInvestisseur($query, $investisseurId)
    {
        return $query->where('investisseur_id', $investisseurId);
    }

    public function scopeParProjet($query, $projetId)
    {
        return $query->where('projet_id', $projetId);
    }

    // Methods
    public function approuver($dateApprobation = null)
    {
        $this->update([
            'statut' => StatutSouscription::APPROVED,
            'date_approbation' => $dateApprobation ?? now(),
        ]);

        // Mettre à jour le nombre d'investisseurs du projet
        $this->projet->update([
            'nombre_investisseurs' => $this->projet->souscriptions()->approuvee()->count(),
        ]);
    }

    public function rejeter($raison)
    {
        $this->update([
            'statut' => StatutSouscription::REJECTED,
            'raison_rejet' => $raison,
            'date_rejet' => now(),
        ]);
    }

    public function mettreAJourMontantRestant()
    {
        $this->update([
            'montant_restant' => max(0, $this->montant_souscrit - $this->montant_paye),
        ]);
    }

    public function estCompletee(): bool
    {
        return $this->montant_paye >= $this->montant_souscrit;
    }

    public function pourcentagePaiement(): float
    {
        if ($this->montant_souscrit == 0) return 0;
        return ($this->montant_paye / $this->montant_souscrit) * 100;
    }

    public function genererNumeroSouscription()
    {
        if (!$this->numero_souscription) {
            $year = date('Y');
            $count = self::whereYear('created_at', $year)->count() + 1;
            $this->numero_souscription = 'SUB-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
        }
        return $this->numero_souscription;
    }
}
