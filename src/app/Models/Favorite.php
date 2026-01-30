<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question_id'];

    /**
     * Relationship: Favorite belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Favorite belongs to a question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
