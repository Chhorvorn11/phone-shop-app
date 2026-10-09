@extends('layouts.shop')
@section('title', 'Register')
@section('content')
<h1>Create an account</h1>
<form method="POST" action="{{ route('register') }}" class="panel" style="max-width:380px">@csrf
  <label>Name</label><input name="name" value="{{ old('name') }}" required autofocus>
  <label>Email</label><input type="email" name="email" value="{{ old('email') }}" required>
  <label>Password (8+ characters)</label><input type="password" name="password" required>
  <label>Confirm password</label><input type="password" name="password_confirmation" required>
  <p><button class="primary">Create account</button></p>
  <p class="mute">Already registered? <a href="{{ route('login') }}">Sign in</a></p>
</form>
@endsection
