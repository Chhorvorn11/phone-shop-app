@extends('layouts.shop')
@section('title', 'Payments')
@section('content')
<h1>Payments</h1>
<form method="POST" action="{{ route('admin.payments.store') }}" class="row panel" style="margin-bottom:16px">@csrf
  <select class="grow" name="booking_id" required>
    <option value="">Choose booking</option>
    @foreach ($bookings as $b)<option value="{{ $b->id }}">#{{ $b->id }} {{ $b->user->name }}, {{ $b->phone->name }} (${{ number_format($b->total_amount) }})</option>@endforeach
  </select>
  <input name="amount" type="number" min="1" placeholder="Amount" style="width:110px" required>
  <select name="method"><option>cash</option><option>card</option><option>transfer</option></select>
  <input name="reference" placeholder="Reference" style="width:140px">
  <button class="primary">Record payment</button>
</form>
<div class="panel"><table>
  <tr><th>Date</th><th>Booking</th><th>Amount</th><th>Method</th><th>Status</th><th></th></tr>
  @forelse ($payments as $p)
    <tr>
      <td>{{ $p->paid_at?->format('d M H:i') }}</td>
      <td>#{{ $p->booking_id }} {{ $p->booking->user->name }}<div class="mute">{{ $p->booking->phone->name }}</div></td>
      <td>${{ number_format($p->amount) }}</td><td>{{ $p->method }}</td>
      <td><span class="badge {{ $p->status === 'paid' ? 's-completed' : 's-cancelled' }}">{{ $p->status }}</span></td>
      <td>@if ($p->status === 'paid')<form class="inline" method="POST" action="{{ route('admin.payments.refund', $p) }}" onsubmit="return confirm('Mark as refunded?')">@csrf @method('PATCH')<button class="danger">Refund</button></form>@endif</td>
    </tr>
  @empty <tr><td colspan="6" class="mute">No payments yet.</td></tr> @endforelse
</table></div>
<div style="margin-top:14px">{{ $payments->links() }}</div>
@endsection
