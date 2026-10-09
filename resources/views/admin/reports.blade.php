@extends('layouts.shop')
@section('title', 'Reports')
@section('content')
<h1>Reports</h1>
<form method="GET" class="row" style="margin-bottom:14px">
  <input type="date" name="from" value="{{ $from }}"><input type="date" name="to" value="{{ $to }}"><button class="primary">Update report</button>
</form>
<div class="stats">
  <div class="panel stat"><b>${{ number_format($revenue) }}</b><span>Revenue (completed bookings)</span></div>
  <div class="panel stat"><b>${{ number_format($collected) }}</b><span>Payments collected</span></div>
  <div class="panel stat"><b>${{ number_format($spent) }}</b><span>Spent on supplier stock</span></div>
</div>
<div class="stats">
  @foreach (['pending', 'confirmed', 'completed', 'cancelled'] as $s)
    <div class="panel stat"><b>{{ $byStatus[$s] ?? 0 }}</b><span>{{ ucfirst($s) }} bookings</span></div>
  @endforeach
</div>
<h2>Top phones by units booked</h2>
<div class="panel"><table>
  <tr><th>Phone</th><th>Units</th></tr>
  @forelse ($top as $t)<tr><td>{{ $t->brand }} {{ $t->name }}</td><td>{{ $t->units }}</td></tr>
  @empty <tr><td colspan="2" class="mute">No bookings in this period.</td></tr> @endforelse
</table></div>
@endsection
