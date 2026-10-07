<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeProjet extends Model
{
    protected $table = 'type_projets';

    protected $fillable = [
        'nom',
        'description',
        'icon',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class, 'type_projet_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
