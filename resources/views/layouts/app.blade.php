<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SecureProject</title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 0; background: #f5f6f8; color: #1f2933; }
        nav { background: #1f3a5f; color: #fff; padding: 12px 24px; display: flex; gap: 16px; align-items: center; }
        nav a, nav button { color: #fff; text-decoration: none; background: none; border: 0; cursor: pointer; font: inherit; }
        nav .spacer { flex: 1; }
        main { max-width: 860px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e4e7eb; }
        input[type=text], input[type=email], input[type=password], textarea { width: 100%; padding: 8px; box-sizing: border-box; margin: 4px 0 12px; }
        button.primary, .btn { background: #1f3a5f; color: #fff; border: 0; padding: 8px 14px; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        button.danger { background: #b42318; color: #fff; border: 0; padding: 8px 14px; border-radius: 4px; cursor: pointer; }
        .flash { background: #e6f4ea; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; }
        .error { background: #fde8e8; padding: 10px 14px; border-radius: 4px; margin-bottom: 16px; color: #8a1f1f; }
        .muted { color: #6b7280; font-size: .9em; }
    </style>
</head>
<body>
<nav>
    <strong>SecureProject</strong>
    @auth
        <a href="{{ route('projects.index') }}">Projects</a>
        @if(auth()->user()->isOrgAdmin())
            <a href="{{ route('team.index') }}">Team</a>
        @endif
        <span class="spacer"></span>
        <span class="muted" style="color:#cbd5e1">{{ auth()->user()->email }} ({{ auth()->user()->organization->name }}, {{ auth()->user()->role }})</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit">Log out</button>
        </form>
    @endauth
</nav>
<main>
    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @yield('content')
</main>
</body>
</html>
