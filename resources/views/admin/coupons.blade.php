@extends('layouts.shop')
@section('title', 'Coupons')
@section('content')
<h1>Coupons</h1>
<form method="POST" action="{{ route('admin.coupons.store') }}" class="row panel" style="margin-bottom:16px">@csrf
  <input name="code" placeholder="CODE" style="width:130px" required>
  <input name="percent" type="number" min="1" max="100" placeholder="% off" style="width:90px" required>
  <input name="expires_at" type="date" style="width:160px" title="Expires">
  <input name="max_uses" type="number" min="1" placeholder="Max uses" style="width:110px">
  <label class="row" style="margin:0"><input type="checkbox" name="active" value="1" checked style="width:auto"> Active</label>
  <button class="primary">Add coupon</button>
</form>
<div class="panel"><table>
  <tr><th>Code</th><th>Discount</th><th>Expires</th><th>Used</th><th>Status</th><th></th></tr>
  @forelse ($coupons as $c)
    <tr>
      <td><b>{{ $c->code }}</b>
        <details><summary>Edit</summary>
          <form method="POST" action="{{ route('admin.coupons.update', $c) }}" class="row">@csrf @method('PUT')
            <input name="code" value="{{ $c->code }}" style="width:120px"><input name="percent" type="number" value="{{ $c->percent }}" style="width:80px">
            <input name="expires_at" type="date" value="{{ $c->expires_at?->toDateString() }}" style="width:150px"><input name="max_uses" type="number" value="{{ $c->max_uses }}" style="width:100px">
            <label class="row" style="margin:0"><input type="checkbox" name="active" value="1" @checked($c->active) style="width:auto"> Active</label>
            <button class="primary">Save coupon</button>
          </form>
        </details></td>
      <td>{{ $c->percent }}%</td><td>{{ $c->expires_at?->format('d M Y') ?? 'Never' }}</td>
      <td>{{ $c->used_count }}{{ $c->max_uses ? ' / '.$c->max_uses : '' }}</td>
      <td><span class="badge {{ $c->isUsable() ? 's-completed' : 's-cancelled' }}">{{ $c->isUsable() ? 'usable' : 'not usable' }}</span></td>
      <td><form class="inline" method="POST" action="{{ route('admin.coupons.destroy', $c) }}" onsubmit="return confirm('Delete {{ $c->code }}?')">@csrf @method('DELETE')<button class="danger">Delete</button></form></td>
    </tr>
  @empty <tr><td colspan="6" class="mute">No coupons yet.</td></tr> @endforelse
</table></div>
@endsection
