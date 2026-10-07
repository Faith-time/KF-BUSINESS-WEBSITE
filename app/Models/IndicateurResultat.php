<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicateurResultat extends Model
{
    protected $table = 'indicateur_resultats';

    protected $fillable = [
        'projet_id',
        'nom',
        'description',
        'unite',
        'valeur_cible',
        'valeur_actuelle',
        'valeur_precedente',
        'type_indicateur',
        'date_mesure',
        'prochaine_date_mesure',
        'interpretation',
        'actions_correctives',
    ];

    protected $casts = [
        'valeur_cible' => 'decimal:2',
        'valeur_actuelle' => 'decimal:2',
        'valeur_precedente' => 'decimal:2',
        'date_mesure' => 'date',
        'prochaine_date_mesure' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    // Scopes
    public function scopeParType($query, $type)
    {
        return $query->where('type_indicateur', $type);
    }

    // Methods
    public function variationParRapportAuPrecedent(): float
    {
        if ($this->valeur_precedente == 0) return 0;
        return (($this->valeur_actuelle - $this->valeur_precedente) / $this->valeur_precedente) * 100;
    }

    public function progressionVersLaCible(): float
    {
        if ($this->valeur_cible == 0) return 0;
        return ($this->valeur_actuelle / $this->valeur_cible) * 100;
    }

    public function estAtteint(): bool
    {
        return $this->valeur_actuelle >= $this->valeur_cible;
    }

    public function tendance(): string
    {
        if ($this->valeur_precedente == 0) return 'neutral';
        $variation = $this->variationParRapportAuPrecedent();
        return $variation > 0 ? 'hausse' : ($variation < 0 ? 'baisse' : 'stable');
    }
}
