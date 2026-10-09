@extends('layouts.shop')
@section('title', $phone->exists ? 'Edit phone' : 'Add phone')
@section('content')
<h1>{{ $phone->exists ? 'Edit phone' : 'Add phone' }}</h1>
<form method="POST" enctype="multipart/form-data" class="panel" style="max-width:520px"
      action="{{ $phone->exists ? route('admin.phones.update', $phone) : route('admin.phones.store') }}">
  @csrf @if ($phone->exists) @method('PUT') @endif
  <label>Category</label>
  <select name="category_id"><option value="">None</option>
    @foreach ($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $phone->category_id) == $c->id)>{{ $c->name }}</option>@endforeach
  </select>
  @foreach (['brand' => 'Brand', 'name' => 'Model', 'storage' => 'Storage (e.g. 128 GB)', 'price' => 'Price (USD)', 'stock' => 'Stock'] as $f => $l)
    <label>{{ $l }}</label>
    <input name="{{ $f }}" value="{{ old($f, $phone->$f) }}" type="{{ in_array($f, ['price', 'stock']) ? 'number' : 'text' }}" required>
  @endforeach
  <label>Picture (JPG, PNG or WEBP, up to 2 MB)</label>
  @if ($phone->image)
    <div style="width:180px;margin-bottom:6px">@include('partials.pic', ['url' => $phone->pictureUrl(), 'alt' => $phone->name, 'h' => 120])</div>
    <label class="row"><input type="checkbox" name="remove_image" value="1" style="width:auto"> Remove current picture</label>
  @endif
  <input type="file" name="image" accept="image/*">
  <label>Specs (one per line, like "Battery: 4000 mAh")</label>
  <textarea name="specs" rows="3">{{ old('specs', $phone->exists ? $phone->specs->map(fn ($s) => $s->label.': '.$s->value)->implode("\n") : '') }}</textarea>
  <label>Description</label><textarea name="description" rows="3">{{ old('description', $phone->description) }}</textarea>
  <p class="row"><button class="primary">Save phone</button><a class="btn" href="{{ route('admin.stock') }}">Cancel</a></p>
</form>
@endsection
