<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for a question.
     */
    public function toggle(Question $question)
    {
        $userId = Auth::id();
        
        // Search if already favorited
        $favorite = Favorite::where('user_id', $userId)
                            ->where('question_id', $question->id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'Removed from favorites.';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'question_id' => $question->id
            ]);
            $status = 'Added to favorites!';
        }

        return back()->with('success', $status);
    }
}
