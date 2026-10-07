<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepartitionBudget extends Model
{
    protected $table = 'repartition_budgets';

    protected $fillable = [
        'projet_id',
        'categorie',
        'montant_prevu',
        'montant_reel',
        'description',
        'statut',
        'pourcentage_completion',
        'notes',
    ];

    protected $casts = [
        'montant_prevu' => 'decimal:2',
        'montant_reel' => 'decimal:2',
        'pourcentage_completion' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    // Scopes
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // Methods
    public function difference(): float
    {
        return $this->montant_reel - $this->montant_prevu;
    }

    public function estDeplasse(): bool
    {
        return $this->montant_reel > $this->montant_prevu;
    }

    public function mettreAJourCompletion()
    {
        if ($this->montant_prevu > 0) {
            $completion = ($this->montant_reel / $this->montant_prevu) * 100;
            $this->update(['pourcentage_completion' => min($completion, 100)]);
        }
    }
}
