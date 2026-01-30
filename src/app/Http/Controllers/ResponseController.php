<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    /**
     * Add a response to a specific question.
     */
    public function store(Request $request, Question $question)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $question->responses()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Response added!');
    }
}
