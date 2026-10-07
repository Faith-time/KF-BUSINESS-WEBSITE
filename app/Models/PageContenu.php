<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContenu extends Model
{
    protected $table = 'page_contenus';

    protected $fillable = [
        'slug',
        'titre',
        'contenu',
        'meta_description',
        'meta_keywords',
        'published',
        'published_at',
        'hero_image',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }

    // Methods
    public function publish()
    {
        $this->update([
            'published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update(['published' => false]);
    }
}
