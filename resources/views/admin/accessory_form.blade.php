@extends('layouts.shop')
@section('title', $item->exists ? 'Edit accessory' : 'Add accessory')
@section('content')
<h1>{{ $item->exists ? 'Edit accessory' : 'Add accessory' }}</h1>
<form method="POST" enctype="multipart/form-data" class="panel" style="max-width:520px"
      action="{{ $item->exists ? route('admin.accessories.update', $item) : route('admin.accessories.store') }}">
  @csrf @if ($item->exists) @method('PUT') @endif
  <label>Name</label><input name="name" value="{{ old('name', $item->name) }}" required>
  <label>Type</label>
  <select name="type">@foreach ($types as $t)<option value="{{ $t }}" @selected(old('type', $item->type) === $t)>{{ ucfirst($t) }}</option>@endforeach</select>
  <label>Price (USD)</label><input name="price" type="number" min="0" value="{{ old('price', $item->price) }}" required>
  <label>Stock</label><input name="stock" type="number" min="0" value="{{ old('stock', $item->stock) }}" required>
  <label>Description</label><textarea name="description" rows="3">{{ old('description', $item->description) }}</textarea>
  <label>Picture (JPG, PNG or WEBP, up to 2 MB)</label>
  @if ($item->image)
    <div style="width:180px;margin-bottom:6px">@include('partials.pic', ['url' => $item->pictureUrl(), 'alt' => $item->name, 'h' => 120])</div>
    <label class="row"><input type="checkbox" name="remove_image" value="1" style="width:auto"> Remove current picture</label>
  @endif
  <input type="file" name="image" accept="image/*">
  <p class="row"><button class="primary">Save accessory</button><a class="btn" href="{{ route('admin.accessories.index') }}">Cancel</a></p>
</form>
@endsection
