<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Q&A - Amine's Guide</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 0; padding: 20px; background: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav { margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; font-weight: bold; }
        .btn { display: inline-block; background: #333; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; border: none; cursor: pointer; }
        .btn-danger { background: #d9534f; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-success { background: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .alert-error { background: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
        .question-card { border-bottom: 1px solid #eee; padding: 15px 0; }
        .question-card:last-child { border-bottom: none; }
        .badge { background: #eee; padding: 2px 8px; border-radius: 10px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <a href="{{ route('home') }}">Home</a>
            @auth
                <a href="{{ route('questions.index') }}">Questions</a>
                <a href="{{ route('dashboard') }}">Dashboard ({{ Auth::user()->role }})</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
