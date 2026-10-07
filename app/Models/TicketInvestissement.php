<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketInvestissement extends Model
{
    protected $table = 'ticket_investissements';

    protected $fillable = [
        'projet_id',
        'nom',
        'description',
        'montant_min',
        'montant_max',
        'montant_disponible',
        'nombre_places_max',
        'nombre_places_disponibles',
        'taux_rendement',
        'type_rendement',
        'date_debut',
        'date_fin',
        'delai_remboursement_mois',
        'conditions',
        'actif',
    ];

    protected $casts = [
        'montant_min' => 'decimal:2',
        'montant_max' => 'decimal:2',
        'montant_disponible' => 'decimal:2',
        'taux_rendement' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class, 'ticket_investissement_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeDisponible($query)
    {
        return $query->where('montant_disponible', '>', 0)
            ->where('nombre_places_disponibles', '>', 0);
    }

    // Methods
    public function placesUtilisees(): int
    {
        return $this->nombre_places_max - $this->nombre_places_disponibles;
    }

    public function montantUtilise(): float
    {
        return $this->montant_max - $this->montant_disponible;
    }

    public function pourcentageCompletionMontant(): float
    {
        if ($this->montant_max == 0) return 0;
        return ($this->montantUtilise() / $this->montant_max) * 100;
    }

    public function pourcentageCompletionPlaces(): float
    {
        if ($this->nombre_places_max == 0) return 0;
        return ($this->placesUtilisees() / $this->nombre_places_max) * 100;
    }

    public function estComplet(): bool
    {
        return $this->montant_disponible <= 0 || $this->nombre_places_disponibles <= 0;
    }
}
