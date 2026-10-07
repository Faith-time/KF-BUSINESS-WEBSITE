<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dividende extends Model
{
    protected $table = 'dividendes';

    protected $fillable = [
        'souscription_id',
        'projet_id',
        'numero_dividende',
        'numero_periode',
        'date_periode_debut',
        'date_periode_fin',
        'montant_brut',
        'taux_dividende',
        'retenue_source',
        'montant_net',
        'statut',
        'date_paiement_prevu',
        'date_paiement_reel',
        'methode_versement',
        'notes',
    ];

    protected $casts = [
        'montant_brut' => 'decimal:2',
        'taux_dividende' => 'decimal:2',
        'retenue_source' => 'decimal:2',
        'montant_net' => 'decimal:2',
        'date_periode_debut' => 'date',
        'date_periode_fin' => 'date',
        'date_paiement_prevu' => 'date',
        'date_paiement_reel' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    // Scopes
    public function scopePlanifie($query)
    {
        return $query->where('statut', 'planifié');
    }

    public function scopeApprouve($query)
    {
        return $query->where('statut', 'approuvé');
    }

    public function scopeVerse($query)
    {
        return $query->where('statut', 'versé');
    }

    public function scopeParPeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_periode_debut', [$debut, $fin]);
    }

    // Methods
    public function approuver()
    {
        $this->update(['statut' => 'approuvé']);
    }

    public function verser($dateVersement = null)
    {
        $this->update([
            'statut' => 'versé',
            'date_paiement_reel' => $dateVersement ?? now(),
        ]);
    }

    public function reporter()
    {
        $this->update(['statut' => 'reporté']);
    }

    public function genererNumeroDividende()
    {
        if (!$this->numero_dividende) {
            $year = date('Y');
            $count = self::whereYear('created_at', $year)->count() + 1;
            $this->numero_dividende = 'DIV-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
        }
        return $this->numero_dividende;
    }

    public function tauxImposition(): float
    {
        if ($this->montant_brut == 0) return 0;
        return ($this->retenue_source / $this->montant_brut) * 100;
    }

    public function pourcentageVersement(): float
    {
        if ($this->montant_brut == 0) return 0;
        return ($this->montant_net / $this->montant_brut) * 100;
    }
}
