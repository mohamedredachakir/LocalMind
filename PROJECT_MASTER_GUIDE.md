# 📄 Community Q&A: Project Master Guide

This guide contains the complete technical architecture and logic for the "Community Q&A" application. This project was built from scratch using manual methods to demonstrate the core mechanics of Laravel.

---

## 🏗 1. Setup & Environment

We use Docker to containerize the PHP 8.2-fpm, Nginx, and PostgreSQL environment.

### `docker-compose.yml`
```yaml
services:
  php:
    build: .
    container_name: php
    volumes:
      - "./src:/var/www/html"
    working_dir: /var/www/html
    depends_on:
      - postgres

  nginx:
    image: nginx:alpine
    container_name: nginx
    ports:
      - "3535:80"
    volumes:
      - "./src:/var/www/html"
      - "./nginx/default.conf:/etc/nginx/conf.d/default.conf"
    depends_on:
      - php

  postgres:
    image: postgres:17-alpine
    container_name: postgres
    restart: always
    environment:
      POSTGRES_DB: my_db
      POSTGRES_USER: reddcs
      POSTGRES_PASSWORD: reddcs
    ports:
      - "2004:5432"
    volumes:
      - pgtables:/var/lib/postgresql/data

volumes:
  pgtables:
```

### `dockerfile`
```dockerfile
FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    zip \
    postgresql-libs \
    nodejs \
    npm

# Install build dependencies for PostgreSQL extension
RUN apk add --no-cache --virtual .build-deps \
    postgresql-dev \
    build-base \
    autoconf \
    re2c \
    libtool \
    make \
    pkgconfig \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
```

---

## 🔐 2. Manual Authentication Logic

We built authentication manually to understand session management and validation without external packages.

### `app/Http/Controllers/AuthController.php`
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', 
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'Credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
```

---

## 🗄 3. Database Architecture

### Migrations (Schema)
*   **Users**: Includes `role` column (default: 'user').
*   **Questions**: `user_id`, `title`, `content`, `location`.
*   **Responses**: `user_id`, `question_id`, `content`.
*   **Favorites**: `user_id`, `question_id`.

### Eloquent Relationships
1.  **User**: `hasMany(Question)`, `hasMany(Response)`, `hasMany(Favorite)`.
2.  **Question**: `belongsTo(User)`, `hasMany(Response)`, `hasMany(Favorite)`.
3.  **Response**: `belongsTo(User)`, `belongsTo(Question)`.
4.  **Favorite**: `belongsTo(User)`, `belongsTo(Question)`.

---

## 🛡 4. Roles & Middlewares

We implement **Admin**, **Editor**, and **User** roles using a custom middleware.

### `app/Http/Middleware/CheckRole.php`
```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if ($request->user() && in_array($request->user()->role, $roles)) {
        return $next($request);
    }
    abort(403, 'Unauthorized action.');
}
```

### Registration in `bootstrap/app.php`
```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
]);
```

---

## 🎮 5. Core Controllers (CRUD & Search)

### `app/Http/Controllers/QuestionController.php`
Implements search by keyword and location.
```php
public function index(Request $request)
{
    $query = Question::with('user')->withCount('responses');

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('content', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('location')) {
        $query->where('location', 'like', '%' . $request->location . '%');
    }

    $questions = $query->latest()->paginate(10);
    return view('questions.index', compact('questions'));
}
```

---

## 🛣 6. Routes (`routes/web.php`)

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); })->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    Route::resource('questions', QuestionController::class);
    Route::post('questions/{question}/responses', [ResponseController::class, 'store'])->name('responses.store');
    Route::post('questions/{question}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
```

---

## 🚀 7. Execution Guide (Step-by-Step)

Follow these commands to get the project working from a fresh clone:

1.  **Start Environment**:
    ```bash
    docker-compose up -d --build
    ```
2.  **Install Dependencies**:
    ```bash
    docker exec -it php composer install
    ```
3.  **Config Environment**:
    ```bash
    docker exec -it php cp .env.example .env
    # Note: Ensure DB_HOST=postgres, DB_DATABASE=my_db, DB_USERNAME=reddcs in .env
    ```
4.  **Generate App Key**:
    ```bash
    docker exec -it php php artisan key:generate
    ```
5.  **Run Migrations & Seeders**:
    ```bash
    docker exec -it php php artisan migrate:fresh --seed
    ```
6.  **Access App**:
    Open `http://localhost:3535` in your browser.

**Default Test Accounts (password: `password`):**
- Admin: `admin@amine.com`
- Editor: `editor@amine.com`
- User: `amine@amine.com`
