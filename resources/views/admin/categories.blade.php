@extends('layouts.shop')
@section('title', 'Categories')
@section('content')
<h1>Categories</h1>
<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="row panel" style="margin-bottom:16px">@csrf
  <input class="grow" name="name" placeholder="Category name" required>
  <input class="grow" name="description" placeholder="Description (optional)">
  <input type="file" name="image" accept="image/*" style="max-width:220px">
  <button class="primary">Add category</button>
</form>
<div class="panel"><table>
  <tr><th></th><th>Name</th><th>Phones</th><th></th></tr>
  @forelse ($categories as $c)
    <tr>
      <td style="width:76px">@include('partials.pic', ['url' => $c->pictureUrl(), 'alt' => $c->name, 'h' => 48, 'w' => '64px'])</td>
      <td><b>{{ $c->name }}</b><div class="mute">{{ $c->description }}</div>
        <details><summary>Edit</summary>
          <form method="POST" action="{{ route('admin.categories.update', $c) }}" enctype="multipart/form-data" class="row">@csrf @method('PUT')
            <input class="grow" name="name" value="{{ $c->name }}" required><input class="grow" name="description" value="{{ $c->description }}">
            <input type="file" name="image" accept="image/*" style="max-width:220px">
            @if ($c->image)<label class="row" style="margin:0"><input type="checkbox" name="remove_image" value="1" style="width:auto"> Remove picture</label>@endif
            <button class="primary">Save category</button>
          </form>
        </details></td>
      <td>{{ $c->phones_count }}</td>
      <td><form class="inline" method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit="return confirm('Delete {{ $c->name }}?')">@csrf @method('DELETE')<button class="danger">Delete</button></form></td>
    </tr>
  @empty <tr><td colspan="4" class="mute">No categories yet. Add one above.</td></tr> @endforelse
</table></div>
@endsection
