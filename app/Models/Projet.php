<?php

namespace App\Models;

use App\Enums\StatutProjet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Projet extends Model
{
    protected $table = 'projets';

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'description_courte',
        'type_projet_id',
        'promoteur_id',
        'localisation',
        'montant_total',
        'montant_collecte',
        'montant_min_investissement',
        'duree_mois',
        'taux_rendement_annuel',
        'statut',
        'date_debut',
        'date_fin',
        'date_fermeture_collecte',
        'image_hero',
        'nombre_investisseurs',
        'pourcentage_completion',
        'risques',
        'opportunites',
        'featured',
        'visible',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'montant_collecte' => 'decimal:2',
        'montant_min_investissement' => 'decimal:2',
        'taux_rendement_annuel' => 'decimal:2',
        'pourcentage_completion' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_fermeture_collecte' => 'date',
        'featured' => 'boolean',
        'visible' => 'boolean',
        'statut' => StatutProjet::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function typeProjet(): BelongsTo
    {
        return $this->belongsTo(TypeProjet::class, 'type_projet_id');
    }

    public function promoteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'promoteur_id');
    }

    public function mediaProjet(): HasMany
    {
        return $this->hasMany(MediaProjet::class, 'projet_id');
    }

    public function ticketInvestissement(): HasMany
    {
        return $this->hasMany(TicketInvestissement::class, 'projet_id');
    }

    public function repartitionBudget(): HasMany
    {
        return $this->hasMany(RepartitionBudget::class, 'projet_id');
    }

    public function projectionFinanciere(): HasMany
    {
        return $this->hasMany(ProjectionFinanciere::class, 'projet_id');
    }

    public function indicateurResultat(): HasMany
    {
        return $this->hasMany(IndicateurResultat::class, 'projet_id');
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class, 'projet_id');
    }

    public function dividendes(): HasMany
    {
        return $this->hasMany(Dividende::class, 'projet_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'projet_id');
    }

    public function paiements(): HasManyThrough
    {
        return $this->hasManyThrough(
            Paiement::class,
            Souscription::class,
            'projet_id',
            'souscription_id'
        );
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('visible', true);
    }

    public function scopeEnFinancement($query)
    {
        return $query->where('statut', StatutProjet::FINANCEMENT->value);
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', StatutProjet::EN_COURS->value);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    // Methods
    public function mettreAJourPourcentageCompletion()
    {
        if ($this->montant_total > 0) {
            $pourcentage = ($this->montant_collecte / $this->montant_total) * 100;
            $this->update(['pourcentage_completion' => min($pourcentage, 100)]);
        }
    }

    public function montantRestant()
    {
        return max(0, $this->montant_total - $this->montant_collecte);
    }

    public function estComplet(): bool
    {
        return $this->montant_collecte >= $this->montant_total;
    }

    public function jursDeFinancement(): int
    {
        return now()->diffInDays($this->date_fermeture_collecte);
    }

    public function estOuvert(): bool
    {
        return $this->statut === StatutProjet::FINANCEMENT &&
            now()->lessThan($this->date_fermeture_collecte);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nom);
            }
        });
    }
}
