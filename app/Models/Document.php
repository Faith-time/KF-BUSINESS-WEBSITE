<?php

namespace App\Models;

use App\Enums\TypeDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'titre',
        'type',
        'description',
        'projet_id',
        'createur_id',
        'investisseur_id',
        'chemin_fichier',
        'nom_fichier_original',
        'extension',
        'taille_bytes',
        'mime_type',
        'visible_investisseurs',
        'visible_public',
        'visible_comptables',
        'date_publication',
        'date_expiration',
        'nombre_telechargements',
        'tags',
        'date_dernier_acces',
        'notes',
    ];

    protected $casts = [
        'visible_investisseurs' => 'boolean',
        'visible_public' => 'boolean',
        'visible_comptables' => 'boolean',
        'date_publication' => 'date',
        'date_expiration' => 'date',
        'date_dernier_acces' => 'datetime',
        'tags' => 'array',
        'type' => TypeDocument::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createur_id');
    }

    public function investisseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investisseur_id');
    }

    // Scopes
    public function scopePublique($query)
    {
        return $query->where('visible_public', true);
    }

    public function scopePourInvestisseurs($query)
    {
        return $query->where('visible_investisseurs', true);
    }

    public function scopePourComptables($query)
    {
        return $query->where('visible_comptables', true);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePublie($query)
    {
        return $query->where('date_publication', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_expiration')
                    ->orWhere('date_expiration', '>', now());
            });
    }

    // Methods
    public function publier($datePublication = null)
    {
        $this->update([
            'date_publication' => $datePublication ?? now(),
            'visible_investisseurs' => true,
        ]);
    }

    public function faire_expirer($dateExpiration)
    {
        $this->update(['date_expiration' => $dateExpiration]);
    }

    public function incrementerTelechargements()
    {
        $this->update([
            'nombre_telechargements' => $this->nombre_telechargements + 1,
            'date_dernier_acces' => now(),
        ]);
    }

    public function estExpire(): bool
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }

    public function estPublie(): bool
    {
        return $this->date_publication && $this->date_publication->isPast() && !$this->estExpire();
    }

    public function tailleEnMo(): float
    {
        return round($this->taille_bytes / (1024 * 1024), 2);
    }

    public function tailleEnGo(): float
    {
        return round($this->taille_bytes / (1024 * 1024 * 1024), 2);
    }
}
