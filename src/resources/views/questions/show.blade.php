@extends('layouts.app')

@section('content')
    <div style="border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
        <h1>{{ $question->title }}</h1>
        <div style="margin-bottom: 15px;">
            <span class="badge">Asked by {{ $question->user->name }}</span>
            @if($question->location)
                <span class="badge">Location: {{ $question->location }}</span>
            @endif
        </div>
        
        <p style="white-space: pre-wrap;">{{ $question->content }}</p>

        <div style="display: flex; gap: 10px; align-items: center;">
            <form action="{{ route('favorites.toggle', $question) }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ Auth::user()->favorites->where('question_id', $question->id)->count() ? 'btn-danger' : '' }}">
                    {{ Auth::user()->favorites->where('question_id', $question->id)->count() ? 'Remove Favorite' : 'Add to Favorites' }}
                </button>
            </form>

            @if(Auth::id() === $question->user_id || Auth::user()->role === 'admin')
                <a href="{{ route('questions.edit', $question) }}" class="btn">Edit</a>
                <form action="{{ route('questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Delete this?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endif
        </div>
    </div>

    <h2>Answers</h2>
    @forelse($question->responses as $response)
        <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
            <p>{{ $response->content }}</p>
            <small>By {{ $response->user->name }} on {{ $response->created_at->format('M d, Y') }}</small>
        </div>
    @empty
        <p>No answers yet. Be the first to help!</p>
    @endforelse

    <hr>
    <h3>Your Answer</h3>
    <form action="{{ route('responses.store', $question) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea name="content" rows="4" placeholder="Type your answer here..." required></textarea>
        </div>
        <button type="submit" class="btn">Post Answer</button>
    </form>
@endsection
