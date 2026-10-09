@extends('layouts.shop')
@section('title', 'Contact')
@section('content')
<h1>Contact us</h1>
<form method="POST" action="{{ route('contact.store') }}" class="panel" style="max-width:480px">@csrf
  <label>Name</label><input name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
  <label>Email</label><input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
  <label>Subject</label><input name="subject" value="{{ old('subject') }}">
  <label>Message</label><textarea name="message" rows="5" required>{{ old('message') }}</textarea>
  <p><button class="primary">Send message</button></p>
</form>
@endsection
