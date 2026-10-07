<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'prenom',
        'nom',
        'telephone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationships
     */

    // Pour les investisseurs
    public function profilInvestisseur(): HasOne
    {
        return $this->hasOne(ProfilInvestisseur::class);
    }

    public function dossierKyc(): HasOne
    {
        return $this->hasOne(DossierKyc::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class, 'investisseur_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'investisseur_id');
    }

    // Pour les promoteurs/administrateurs
    public function projetsCrees(): HasMany
    {
        return $this->hasMany(Projet::class, 'promoteur_id');
    }

    // Pour les administrateurs
    public function dossiersKycVerifies(): HasMany
    {
        return $this->hasMany(DossierKyc::class, 'verify_by_id');
    }

    // Documents créés par l'utilisateur
    public function documentsCreated(): HasMany
    {
        return $this->hasMany(Document::class, 'createur_id');
    }

    // Documents assignés à l'utilisateur
    public function documentsAssigned(): HasMany
    {
        return $this->hasMany(Document::class, 'investisseur_id');
    }

    /**
     * Scopes
     */

    public function scopeInvestisseurs($query)
    {
        return $query->role('investisseur');
    }

    public function scopeComptables($query)
    {
        return $query->role('comptable');
    }

    public function scopeAdministrateurs($query)
    {
        return $query->role('administrateur');
    }

    public function scopePromoteurs($query)
    {
        return $query->role('promoteur');
    }

    /**
     * Methods
     */

    public function nomComplet(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function estInvestisseur(): bool
    {
        return $this->hasRole('investisseur');
    }

    public function estComptable(): bool
    {
        return $this->hasRole('comptable');
    }

    public function estAdministrateur(): bool
    {
        return $this->hasRole('administrateur');
    }

    public function estPromoteur(): bool
    {
        return $this->hasRole('promoteur');
    }

    public function kyeApprouve(): bool
    {
        return $this->dossierKyc && $this->dossierKyc->estApprouve();
    }

    public function totalInvesti(): float
    {
        return $this->souscriptions()
            ->where('statut', 'approuvée')
            ->sum('montant_souscrit');
    }

    public function totalVerse(): float
    {
        return $this->souscriptions()
            ->where('statut', 'approuvée')
            ->sum('montant_paye');
    }

    public function nombreProjetsInvestis(): int
    {
        return $this->souscriptions()
            ->where('statut', 'approuvée')
            ->distinct('projet_id')
            ->count('projet_id');
    }

    public function nombreSouscriptionsActives(): int
    {
        return $this->souscriptions()
            ->where('statut', 'approuvée')
            ->where('montant_restant', '>', 0)
            ->count();
    }
}
