@extends('layouts.app')

@section('content')
    <h1>Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <p>Your Role: <span class="badge">{{ Auth::user()->role }}</span></p>

    <h2>Your Questions</h2>
    <ul>
        @forelse(Auth::user()->questions as $question)
            <li><a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a></li>
        @empty
            <li>You haven't asked any questions yet.</li>
        @endforelse
    </ul>
@endsection
