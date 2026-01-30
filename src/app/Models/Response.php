<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Question;

class Response extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'question_id', 'content'];

    /**
     * Relationship: Response belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Response belongs to a question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
