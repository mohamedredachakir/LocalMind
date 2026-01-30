@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>All Questions</h1>
        <a href="{{ route('questions.create') }}" class="btn">Ask Question</a>
    </div>

    <form action="{{ route('questions.index') }}" method="GET" style="background:#eee; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search keywords..." value="{{ request('search') }}" style="flex:2; padding: 8px;">
            <input type="text" name="location" placeholder="Location..." value="{{ request('location') }}" style="flex:1; padding: 8px;">
            <button type="submit" class="btn">Filter</button>
        </div>
    </form>

    @forelse($questions as $question)
        <div class="question-card">
            <h3><a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a></h3>
            <p>{{ Str::limit($question->content, 150) }}</p>
            <div>
                <span class="badge">By {{ $question->user->name }}</span>
                @if($question->location)
                    <span class="badge">{{ $question->location }}</span>
                @endif
                <span class="badge">{{ $question->responses_count }} answers</span>
            </div>
        </div>
    @empty
        <p>No questions found.</p>
    @endforelse

    <div style="margin-top: 20px;">
        {{ $questions->appends(request()->input())->links() }}
    </div>
@endsection
