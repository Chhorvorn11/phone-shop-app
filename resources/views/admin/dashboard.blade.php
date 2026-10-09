@extends('layouts.shop')
@section('title', 'Dashboard')
@section('content')
<h1>Dashboard</h1>
<div class="stats">
  <div class="panel stat"><b>{{ $phones }}</b><span>Phone models</span></div>
  <div class="panel stat"><b>{{ $categories }}</b><span>Categories</span></div>
  <div class="panel stat"><b>{{ $todayCount }}</b><span>Pickups today</span></div>
  <div class="panel stat"><b>{{ $pending }}</b><span>Waiting for confirmation</span></div>
</div>
<h2>Low stock (under 5)</h2>
<div class="panel">
  @forelse ($low as $p)
    <div class="row" style="justify-content:space-between"><span>{{ $p->brand }} {{ $p->name }}</span><span class="low">{{ $p->stock }} left</span></div>
  @empty <span class="mute">All phones are well stocked.</span> @endforelse
</div>
<h2>Latest bookings</h2>
<div class="panel"><table>
  <tr><th>Customer</th><th>Phone</th><th>Pickup</th><th>Status</th></tr>
  @forelse ($recent as $b)
    <tr><td>{{ $b->user->name }}</td><td>{{ $b->phone->name }} x{{ $b->quantity }}</td><td>{{ $b->pickup_date->format('d M') }}</td><td><span class="badge s-{{ $b->status }}">{{ $b->status }}</span></td></tr>
  @empty <tr><td colspan="4" class="mute">No bookings yet.</td></tr> @endforelse
</table></div>
@endsection
