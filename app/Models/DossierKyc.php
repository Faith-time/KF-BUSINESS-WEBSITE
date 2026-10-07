<?php

namespace App\Models;

use App\Enums\StatutKyc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DossierKyc extends Model
{
    protected $table = 'dossier_kycs';

    protected $fillable = [
        'user_id',
        'statut',
        'numero_identification',
        'type_identification',
        'date_delivrance',
        'date_expiration',
        'lieu_delivrance',
        'adresse_complete',
        'ville',
        'code_postal',
        'pays',
        'telephone_verification',
        'telephone_verifie',
        'numero_compte_bancaire',
        'nom_banque',
        'code_swift',
        'adresse_banque',
        'sources_fonds',
        'activite_professionnelle',
        'secteur_activite',
        'experience_investissement',
        'nom_personne_reference',
        'telephone_reference',
        'email_reference',
        'documents_supports',
        'raison_rejet',
        'date_rejet',
        'date_approbation',
        'verify_by_id',
        'notes_verification',
    ];

    protected $casts = [
        'date_delivrance' => 'date',
        'date_expiration' => 'date',
        'telephone_verifie' => 'boolean',
        'documents_supports' => 'array',
        'date_rejet' => 'datetime',
        'date_approbation' => 'datetime',
        'statut' => StatutKyc::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifyBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verify_by_id');
    }

    // Scopes
    public function scopeNonCommence($query)
    {
        return $query->where('statut', StatutKyc::NOT_STARTED->value);
    }

    public function scopeEnRevision($query)
    {
        return $query->where('statut', StatutKyc::UNDER_REVIEW->value);
    }

    public function scopeApprouve($query)
    {
        return $query->where('statut', StatutKyc::APPROVED->value);
    }

    public function scopeRejecte($query)
    {
        return $query->where('statut', StatutKyc::REJECTED->value);
    }

    // Methods
    public function approuver($verifyById, $notes = null)
    {
        $this->update([
            'statut' => StatutKyc::APPROVED,
            'date_approbation' => now(),
            'verify_by_id' => $verifyById,
            'notes_verification' => $notes,
        ]);
    }

    public function rejeter($raison, $verifyById, $notes = null)
    {
        $this->update([
            'statut' => StatutKyc::REJECTED,
            'date_rejet' => now(),
            'raison_rejet' => $raison,
            'verify_by_id' => $verifyById,
            'notes_verification' => $notes,
        ]);
    }

    public function estApprouve(): bool
    {
        return $this->statut === StatutKyc::APPROVED;
    }

    public function estExpire(): bool
    {
        return $this->date_expiration && $this->date_expiration->isPast();
    }
}
