@extends('layouts.app')

@section('content')
    <h1>Login</h1>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
@endsection
