@extends('layouts.shop')
@section('title', 'Wishlist')
@section('content')
<h1>My wishlist</h1>
<div class="grid">
@forelse ($phones as $p)
  <div class="panel">
    <div style="margin-bottom:8px">@include('partials.pic', ['url' => $p->pictureUrl(), 'alt' => $p->name, 'h' => 130])</div>
    <div class="mute">{{ $p->category?->name ?? 'Uncategorised' }}</div>
    <h2 style="margin:2px 0">{{ $p->brand }} {{ $p->name }}</h2>
    <div class="row" style="justify-content:space-between"><b>${{ number_format($p->price) }}</b><span class="mute">{{ $p->stock }} in stock</span></div>
    <form method="POST" action="{{ route('wishlist.toggle', $p) }}" style="margin-top:8px">@csrf<button class="danger">Remove</button></form>
  </div>
@empty
  <p class="mute">Nothing saved yet. <a href="{{ route('home') }}">Browse phones</a> and press Save.</p>
@endforelse
</div>
@endsection
