@extends('layouts.app')

@section('content')
<div class="card" style="max-width:420px;margin:40px auto">
    <h2>Sign in</h2>
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="primary" type="submit">Log in</button>
    </form>
    <p class="muted">Training lab. Use the seeded test accounts from the lab README.</p>
</div>
@endsection
