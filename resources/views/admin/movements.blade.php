@extends('layouts.shop')
@section('title', 'Stock log')
@section('content')
<h1>Stock log</h1>
<div class="panel"><table>
  <tr><th>When</th><th>Phone</th><th>Type</th><th>Change</th><th>Reason</th><th>By</th></tr>
  @forelse ($rows as $m)
    <tr>
      <td>{{ $m->created_at->format('d M H:i') }}</td><td>{{ $m->phone->brand }} {{ $m->phone->name }}</td>
      <td><span class="badge">{{ $m->type }}</span></td>
      <td class="{{ $m->quantity < 0 ? 'low' : '' }}">{{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
      <td>{{ $m->reason }}</td><td>{{ $m->user?->name ?? 'System' }}</td>
    </tr>
  @empty <tr><td colspan="6" class="mute">No stock changes yet.</td></tr> @endforelse
</table></div>
<div style="margin-top:14px">{{ $rows->links() }}</div>
@endsection
