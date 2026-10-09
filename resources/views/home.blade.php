@extends('layouts.shop')
@section('title', 'Shop')
@section('content')
@if ($banners->count())
<div class="grid" style="margin-bottom:18px">
  @foreach ($banners as $b)
    <a class="panel" href="{{ $b->link_url ?: route('accessories') }}" style="padding:0;overflow:hidden;color:var(--ink)">
      @if ($b->pictureUrl())<img src="{{ $b->pictureUrl() }}" alt="{{ $b->title }}" style="width:100%;height:140px;object-fit:cover;display:block">@endif
      <div style="padding:12px"><b>{{ $b->title }}</b><div class="mute">{{ $b->subtitle }}</div></div>
    </a>
  @endforeach
</div>
@endif
<h1>Phones</h1>
<form method="GET" class="row" style="margin-bottom:16px">
  <input class="grow" name="q" value="{{ request('q') }}" placeholder="Search brand or model">
  <select name="category" onchange="this.form.submit()">
    <option value="">All categories</option>
    @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <button class="primary">Search</button>
</form>
<div class="grid">
@forelse ($phones as $p)
  <div class="panel">
    <div style="margin-bottom:8px">@include('partials.pic', ['url' => $p->pictureUrl(), 'alt' => $p->name, 'h' => 150])</div>
    <div class="row" style="justify-content:space-between">
      <span class="mute">{{ $p->category?->name ?? 'Uncategorised' }}</span>
      @auth<form class="inline" method="POST" action="{{ route('wishlist.toggle', $p) }}">@csrf<button>{{ in_array($p->id, $saved) ? 'Saved' : 'Save' }}</button></form>@endauth
    </div>
    <h2 style="margin:2px 0">{{ $p->brand }} {{ $p->name }}</h2>
    <div class="mute">{{ $p->storage }} &middot; {{ $p->reviews_count ? number_format($p->reviews_avg_rating, 1).' / 5 ('.$p->reviews_count.')' : 'No reviews yet' }}</div>
    @if ($p->specs->count())
      <div class="mute">@foreach ($p->specs as $s){{ $s->label }}: {{ $s->value }}@if (! $loop->last) | @endif @endforeach</div>
    @endif
    <div class="row" style="justify-content:space-between;margin:8px 0">
      <b style="font-size:20px">${{ number_format($p->price) }}</b>
      <span class="{{ $p->stock < 5 ? 'low' : 'mute' }}">{{ $p->stock ? $p->stock.' in stock' : 'Sold out' }}</span>
    </div>
    @if ($p->stock > 0)
      @auth
        <details><summary>Book this phone</summary>
          <form method="POST" action="{{ route('bookings.store', $p) }}">@csrf
            <label>Quantity</label><input type="number" name="quantity" value="1" min="1" max="{{ min(5, $p->stock) }}">
            <label>Pickup date</label><input type="date" name="pickup_date" value="{{ now()->addDay()->toDateString() }}" min="{{ now()->toDateString() }}">
            <label>Coupon code (optional)</label><input name="coupon" maxlength="30">
            <label>Note (optional)</label><input name="note" maxlength="200">
            <p><button class="primary">Place booking</button></p>
          </form>
        </details>
      @else
        <a href="{{ route('login') }}">Sign in to book</a>
      @endauth
    @endif
    <details><summary>Reviews ({{ $p->reviews_count }})</summary>
      @foreach ($p->reviews->take(3) as $rv)<div class="mute"><b>{{ $rv->rating }}/5</b> {{ $rv->user->name }}: {{ $rv->comment }}</div>@endforeach
      @auth
        <form method="POST" action="{{ route('reviews.store', $p) }}">@csrf
          <label>Your rating</label><select name="rating">@foreach ([5, 4, 3, 2, 1] as $n)<option>{{ $n }}</option>@endforeach</select>
          <label>Comment</label><input name="comment" maxlength="500">
          <p><button>Post review</button></p>
        </form>
      @endauth
    </details>
  </div>
@empty
  <p class="mute">No phones match your search.</p>
@endforelse
</div>
<div style="margin-top:16px">{{ $phones->links() }}</div>
@endsection
