<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Response;
use App\Models\Favorite;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content', 'location'];

    /**
     * Relationship: Question belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Question has many responses.
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Relationship: Question has many favorites.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
