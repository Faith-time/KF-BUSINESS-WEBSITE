<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectionFinanciere extends Model
{
    protected $table = 'projection_financieres';

    protected $fillable = [
        'projet_id',
        'mois_projection',
        'date_projection',
        'revenus_projetes',
        'charges_projetes',
        'flux_tresorerie',
        'benefice_net_projete',
        'taux_croissance',
        'assumptions',
        'notes',
    ];

    protected $casts = [
        'date_projection' => 'date',
        'revenus_projetes' => 'decimal:2',
        'charges_projetes' => 'decimal:2',
        'flux_tresorerie' => 'decimal:2',
        'benefice_net_projete' => 'decimal:2',
        'taux_croissance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    // Scopes
    public function scopeParPeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_projection', [$debut, $fin]);
    }

    // Methods
    public function margeNette(): float
    {
        if ($this->revenus_projetes == 0) return 0;
        return ($this->benefice_net_projete / $this->revenus_projetes) * 100;
    }

    public function ratioCharge(): float
    {
        if ($this->revenus_projetes == 0) return 0;
        return ($this->charges_projetes / $this->revenus_projetes) * 100;
    }
}
