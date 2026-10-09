@extends('layouts.shop')
@section('title', 'My bookings')
@section('content')
<h1>My bookings</h1>
<div class="panel">
<table>
  <tr><th>Phone</th><th>Qty</th><th>Pickup</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr>
  @forelse ($bookings as $b)
    @php($paid = $b->payments->where('status', 'paid')->sum('amount'))
    <tr>
      <td>{{ $b->phone->brand }} {{ $b->phone->name }}</td><td>{{ $b->quantity }}</td>
      <td>{{ $b->pickup_date->format('d M Y') }}</td>
      <td>${{ number_format($b->total_amount) }}@if ($b->discount_amount)<div class="mute">{{ $b->coupon?->code }} saved ${{ number_format($b->discount_amount) }}</div>@endif</td>
      <td>{{ $paid >= $b->total_amount && $paid > 0 ? 'Paid' : ($paid ? 'Paid $'.number_format($paid) : 'Pay at pickup') }}</td>
      <td><span class="badge s-{{ $b->status }}">{{ $b->status }}</span></td>
      <td>@if ($b->status === 'pending')
        <form class="inline" method="POST" action="{{ route('bookings.cancel', $b) }}" onsubmit="return confirm('Cancel this booking?')">@csrf @method('PATCH')<button class="danger">Cancel</button></form>
      @endif</td>
    </tr>
  @empty
    <tr><td colspan="7" class="mute">No bookings yet. <a href="{{ route('home') }}">Browse phones</a> to book one.</td></tr>
  @endforelse
</table>
</div>
@endsection
