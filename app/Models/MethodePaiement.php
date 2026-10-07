<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MethodePaiement extends Model
{
    protected $table = 'methode_paiements';

    protected $fillable = [
        'nom',
        'description',
        'code',
        'active',
        'config',
        'frais_pourcentage',
        'frais_fixes',
        'icon',
    ];

    protected $casts = [
        'config' => 'array',
        'frais_pourcentage' => 'decimal:2',
        'frais_fixes' => 'decimal:2',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'methode_paiement_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Methods
    public function calculerFrais(float $montant): float
    {
        $pourcentage = ($montant * $this->frais_pourcentage) / 100;
        return $pourcentage + $this->frais_fixes;
    }
}
