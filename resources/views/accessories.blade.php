@extends('layouts.shop')
@section('title', 'Accessories')
@section('content')
<h1>Accessories</h1>
<form method="GET" class="row" style="margin-bottom:16px">
  <input class="grow" name="q" value="{{ request('q') }}" placeholder="Search accessories">
  <select name="type" onchange="this.form.submit()">
    <option value="">All types</option>
    @foreach ($types as $t)<option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>@endforeach
  </select>
  <button class="primary">Search</button>
</form>
<div class="grid">
@forelse ($items as $a)
  <div class="panel">
    <div style="margin-bottom:8px">@include('partials.pic', ['url' => $a->pictureUrl(), 'alt' => $a->name, 'h' => 150])</div>
    <span class="badge">{{ $a->type }}</span>
    <h2 style="margin:6px 0 2px">{{ $a->name }}</h2>
    <div class="mute">{{ $a->description }}</div>
    <div class="row" style="justify-content:space-between;margin-top:8px">
      <b style="font-size:20px">${{ number_format($a->price) }}</b>
      <span class="{{ $a->stock < 5 ? 'low' : 'mute' }}">{{ $a->stock ? $a->stock.' in stock' : 'Sold out' }}</span>
    </div>
  </div>
@empty <p class="mute">No accessories found.</p> @endforelse
</div>
<div style="margin-top:16px">{{ $items->links() }}</div>
@endsection
