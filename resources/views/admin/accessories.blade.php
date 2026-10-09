@extends('layouts.shop')
@section('title', 'Accessories')
@section('content')
<div class="row" style="justify-content:space-between;margin-bottom:12px">
  <h1 style="margin:0">Accessories</h1>
  <a class="btn primary" href="{{ route('admin.accessories.create') }}">Add accessory</a>
</div>
<div class="panel"><table>
  <tr><th></th><th>Name</th><th>Type</th><th>Price</th><th>Stock</th><th></th></tr>
  @forelse ($items as $a)
    <tr>
      <td style="width:76px">@include('partials.pic', ['url' => $a->pictureUrl(), 'alt' => $a->name, 'h' => 48, 'w' => '64px'])</td>
      <td><b>{{ $a->name }}</b></td><td>{{ $a->type }}</td><td>${{ number_format($a->price) }}</td>
      <td class="{{ $a->stock < 5 ? 'low' : '' }}">{{ $a->stock }}</td>
      <td class="row"><a href="{{ route('admin.accessories.edit', $a) }}">Edit</a>
        <form class="inline" method="POST" action="{{ route('admin.accessories.destroy', $a) }}" onsubmit="return confirm('Delete {{ $a->name }}?')">@csrf @method('DELETE')<button class="danger">Delete</button></form></td>
    </tr>
  @empty <tr><td colspan="6" class="mute">No accessories yet.</td></tr> @endforelse
</table></div>
<div style="margin-top:14px">{{ $items->links() }}</div>
@endsection
