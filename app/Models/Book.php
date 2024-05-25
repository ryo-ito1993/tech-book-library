<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function authors(): HasMany
    {
        return $this->hasMany(BookAuthor::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_books');
    }

    public function reads(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'read_books');
    }
}
