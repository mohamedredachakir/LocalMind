@extends('layouts.app')

@section('content')
    <h1>Ask a Question</h1>
    <form action="{{ route('questions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group">
            <label for="location">Location (Optional)</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="e.g. Center City, North Park">
        </div>
        <div class="form-group">
            <label for="content">Description</label>
            <textarea name="content" id="content" rows="5" required>{{ old('content') }}</textarea>
        </div>
        <button type="submit" class="btn">Post Question</button>
    </form>
@endsection
