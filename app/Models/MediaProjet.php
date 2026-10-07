<?php

namespace App\Models;

use App\Enums\TypeMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaProjet extends Model
{
    protected $table = 'media_projets';

    protected $fillable = [
        'projet_id',
        'type',
        'chemin_fichier',
        'nom_fichier_original',
        'mime_type',
        'taille_bytes',
        'description',
        'ordre',
        'principal',
    ];

    protected $casts = [
        'type' => TypeMedia::class,
        'principal' => 'boolean',
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
        return $query->where('type', $type);
    }

    public function scopePrincipal($query)
    {
        return $query->where('principal', true);
    }

    // Methods
    public function estImage(): bool
    {
        return $this->type === TypeMedia::IMAGE;
    }

    public function estVideo(): bool
    {
        return $this->type === TypeMedia::VIDEO;
    }

    public function tailleEnMo(): float
    {
        return round($this->taille_bytes / (1024 * 1024), 2);
    }
}
