@extends('layouts.shop')
@section('title', 'Stock')
@section('content')
<div class="row" style="justify-content:space-between;margin-bottom:12px">
  <h1 style="margin:0">Stock</h1>
  <div class="row">
    <a class="btn" href="{{ request('low') ? route('admin.stock') : route('admin.stock', ['low' => 1]) }}">{{ request('low') ? 'Show all' : 'Low stock only' }}</a>
    <a class="btn primary" href="{{ route('admin.phones.create') }}">Add phone</a>
  </div>
</div>
<div class="panel"><table>
  <tr><th></th><th>Phone</th><th>Category</th><th>Storage</th><th>Price</th><th>Stock</th><th></th></tr>
  @forelse ($phones as $p)
    <tr>
      <td style="width:64px">@include('partials.pic', ['url' => $p->pictureUrl(), 'alt' => $p->name, 'h' => 44, 'w' => '56px'])</td>
      <td><b>{{ $p->brand }}</b> {{ $p->name }}</td><td>{{ $p->category?->name ?? 'None' }}</td><td>{{ $p->storage }}</td>
      <td>${{ number_format($p->price) }}</td><td class="{{ $p->stock < 5 ? 'low' : '' }}">{{ $p->stock }}</td>
      <td class="row"><a href="{{ route('admin.phones.edit', $p) }}">Edit</a>
        <form class="inline" method="POST" action="{{ route('admin.phones.destroy', $p) }}" onsubmit="return confirm('Delete {{ $p->name }} and its bookings?')">@csrf @method('DELETE')<button class="danger">Delete</button></form></td>
    </tr>
  @empty <tr><td colspan="7" class="mute">No phones found.</td></tr> @endforelse
</table></div>
<div style="margin-top:14px">{{ $phones->links() }}</div>
@endsection
