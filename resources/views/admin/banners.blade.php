@extends('layouts.shop')
@section('title', 'Banners')
@section('content')
<div class="row" style="justify-content:space-between;margin-bottom:12px">
  <h1 style="margin:0">Home page banners</h1>
  <a class="btn primary" href="{{ route('admin.banners.create') }}">Add banner</a>
</div>
<p class="mute">The first 3 active banners (lowest order first) are shown above the phones on the shop page.</p>
<div class="panel"><table>
  <tr><th>Picture</th><th>Title</th><th>Order</th><th>Status</th><th></th></tr>
  @forelse ($banners as $b)
    <tr>
      <td style="width:130px">@include('partials.pic', ['url' => $b->pictureUrl(), 'alt' => $b->title, 'h' => 56, 'w' => '112px'])</td>
      <td><b>{{ $b->title }}</b><div class="mute">{{ $b->subtitle }}</div></td><td>{{ $b->sort_order }}</td>
      <td><span class="badge {{ $b->active ? 's-completed' : 's-cancelled' }}">{{ $b->active ? 'shown' : 'hidden' }}</span></td>
      <td class="row"><a href="{{ route('admin.banners.edit', $b) }}">Edit</a>
        <form class="inline" method="POST" action="{{ route('admin.banners.destroy', $b) }}" onsubmit="return confirm('Delete this banner?')">@csrf @method('DELETE')<button class="danger">Delete</button></form></td>
    </tr>
  @empty <tr><td colspan="5" class="mute">No banners yet.</td></tr> @endforelse
</table></div>
@endsection
