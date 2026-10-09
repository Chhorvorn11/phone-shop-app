@extends('layouts.shop')
@section('title', 'Purchases')
@section('content')
<div class="row" style="justify-content:space-between;margin-bottom:12px">
  <h1 style="margin:0">Purchases from suppliers</h1>
  <a class="btn primary" href="{{ route('admin.purchases.create') }}">Receive stock</a>
</div>
<div class="panel"><table>
  <tr><th>Date</th><th>Reference</th><th>Supplier</th><th>Lines</th><th>Total cost</th></tr>
  @forelse ($purchases as $p)
    <tr><td>{{ $p->purchased_at->format('d M Y') }}</td><td>{{ $p->reference }}</td><td>{{ $p->supplier->name }}</td><td>{{ $p->items_count }}</td><td>${{ number_format($p->total_cost) }}</td></tr>
  @empty <tr><td colspan="5" class="mute">No purchases yet.</td></tr> @endforelse
</table></div>
<div style="margin-top:14px">{{ $purchases->links() }}</div>
@endsection
