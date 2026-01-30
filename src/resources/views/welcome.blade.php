@extends('layouts.app')

@section('content')
    <h1>Welcome to Community Q&A</h1>
    <p>This is a custom Laravel application built from scratch to help new residents like Amine navigate the city.</p>
    
    @guest
        <p>Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to start asking questions.</p>
    @else
        <p>Hello, {{ Auth::user()->name }}! Check out the <a href="{{ route('questions.index') }}">latest questions</a>.</p>
    @endguest
@endsection
