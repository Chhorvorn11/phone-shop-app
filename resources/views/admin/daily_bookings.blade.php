@extends('layouts.shop')
@section('title', 'Daily bookings')
@section('content')
<h1>Daily bookings</h1>
<form method="GET" class="row" style="margin-bottom:14px">
  <input type="date" name="date" value="{{ $date }}"><button class="primary">Show pickups</button>
</form>
<div class="panel"><table>
  <tr><th>Customer</th><th>Phone</th><th>Qty</th><th>Total</th><th>Note</th><th>Status</th><th></th></tr>
  @forelse ($bookings as $b)
    <tr>
      <td>{{ $b->user->name }}<div class="mute">{{ $b->user->email }} {{ $b->profile?->phone_number }}</div></td>
      <td>{{ $b->phone->brand }} {{ $b->phone->name }}</td><td>{{ $b->quantity }}</td><td>${{ number_format($b->total_amount) }}</td><td>{{ $b->note }}</td>
      <td><span class="badge s-{{ $b->status }}">{{ $b->status }}</span></td>
      <td class="row">
        @php($next = ['pending' => ['confirmed', 'cancelled'], 'confirmed' => ['completed', 'cancelled']][$b->status] ?? [])
        @foreach ($next as $s)
          <form class="inline" method="POST" action="{{ route('admin.bookings.update', $b) }}">@csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $s }}"><button class="{{ $s === 'cancelled' ? 'danger' : '' }}">{{ ucfirst($s === 'confirmed' ? 'Confirm' : ($s === 'completed' ? 'Complete' : 'Cancel')) }}</button>
          </form>
        @endforeach
      </td>
    </tr>
  @empty <tr><td colspan="7" class="mute">No pickups on this date.</td></tr> @endforelse
</table></div>
@endsection
