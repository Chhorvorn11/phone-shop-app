@extends('layouts.shop')
@section('title', 'Sign in')
@section('content')
<h1>Sign in</h1>
<form method="POST" action="{{ route('login') }}" class="panel" style="max-width:380px">@csrf
  <label>Email</label><input type="email" name="email" value="{{ old('email') }}" required autofocus>
  <label>Password</label><input type="password" name="password" required>
  <label class="row" style="margin-top:12px"><input type="checkbox" name="remember" style="width:auto"> Keep me signed in</label>
  <p><button class="primary">Sign in</button></p>
  <p class="mute">No account? <a href="{{ route('register') }}">Register</a></p>
  @if (app()->environment('local'))
    <p class="mute">Demo: admin@example.com or user@example.com, password: password</p>
  @endif
</form>
@endsection
