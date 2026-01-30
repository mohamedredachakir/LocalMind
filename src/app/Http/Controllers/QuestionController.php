<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions with search.
     */
    public function index(Request $request)
    {
        // Eloquent Query Builder
        $query = Question::with('user')->withCount('responses');

        // Search by keyword in title/content
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Search by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $questions = $query->latest()->paginate(10);

        return view('questions.index', compact('questions'));
    }

    /**
     * Show form to create a new question.
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a new question in DB.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'location' => 'nullable|string|max:100',
        ]);

        // Manually associating the user ID
        Auth::user()->questions()->create($validated);

        return redirect()->route('questions.index')->with('success', 'Question posted!');
    }

    /**
     * Display a specific question with its responses.
     */
    public function show(Question $question)
    {
        $question->load(['responses.user', 'user']);
        return view('questions.show', compact('question'));
    }

    /**
     * Show form to edit question (Only owner or admin).
     */
    public function edit(Question $question)
    {
        // Simple manual check (Policy-like logic)
        if (Auth::id() !== $question->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('questions.edit', compact('question'));
    }

    /**
     * Update the question.
     */
    public function update(Request $request, Question $question)
    {
        if (Auth::id() !== $question->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'location' => 'nullable|string|max:100',
        ]);

        $question->update($validated);

        return redirect()->route('questions.show', $question)->with('success', 'Updated!');
    }

    /**
     * Delete the question.
     */
    public function destroy(Question $question)
    {
        if (Auth::id() !== $question->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Deleted!');
    }
}
