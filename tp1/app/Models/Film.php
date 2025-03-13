<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{

    protected $fillable = [
        'title', 
        'release_year', 
        'length', 
        'description',
        'rating', 
        'language_id', 
        'special_features', 
        'image'
    ];

    public function averageScore()
    {
        return $this->critics()->avg('score');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function critics()
    {
        return $this->hasMany(Critic::class, 'film_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }
}
