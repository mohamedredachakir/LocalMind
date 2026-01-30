@extends('layouts.app')

@section('content')
    <h1>Edit Question</h1>
    <form action="{{ route('questions.update', $question) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $question->title) }}" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" value="{{ old('location', $question->location) }}">
        </div>
        <div class="form-group">
            <label for="content">Description</label>
            <textarea name="content" id="content" rows="5" required>{{ old('content', $question->content) }}</textarea>
        </div>
        <button type="submit" class="btn">Update Question</button>
        <a href="{{ route('questions.show', $question) }}" class="btn" style="background:gray">Cancel</a>
    </form>
@endsection
