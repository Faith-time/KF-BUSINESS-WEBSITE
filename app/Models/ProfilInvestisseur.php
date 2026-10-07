<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilInvestisseur extends Model
{
    protected $table = 'profil_investisseurs';

    protected $fillable = [
        'user_id',
        'type_investisseur',
        'montant_min_investissement',
        'montant_max_investissement',
        'secteur_interet',
        'risque_preference',
        'experience_investissement',
        'localisation',
        'devise_preference',
        'notifications_email',
        'notifications_sms',
        'preferences_additionnelles',
    ];

    protected $casts = [
        'montant_min_investissement' => 'decimal:2',
        'montant_max_investissement' => 'decimal:2',
        'notifications_email' => 'boolean',
        'notifications_sms' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeParTypeInvestisseur($query, $type)
    {
        return $query->where('type_investisseur', $type);
    }

    public function scopeParRisque($query, $risque)
    {
        return $query->where('risque_preference', $risque);
    }
}
