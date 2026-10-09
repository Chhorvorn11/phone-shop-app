@extends('layouts.shop')
@section('title', $banner->exists ? 'Edit banner' : 'Add banner')
@section('content')
<h1>{{ $banner->exists ? 'Edit banner' : 'Add banner' }}</h1>
<form method="POST" enctype="multipart/form-data" class="panel" style="max-width:520px"
      action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}">
  @csrf @if ($banner->exists) @method('PUT') @endif
  <label>Title</label><input name="title" value="{{ old('title', $banner->title) }}" required>
  <label>Subtitle</label><input name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
  <label>Link (optional, starts with https://)</label><input name="link_url" value="{{ old('link_url', $banner->link_url) }}">
  <label>Order (lowest first)</label><input name="sort_order" type="number" min="0" value="{{ old('sort_order', $banner->sort_order) }}">
  <label>Picture (JPG, PNG or WEBP, up to 2 MB){{ $banner->exists ? '' : ', required' }}</label>
  @if ($banner->image)
    <div style="width:220px;margin-bottom:6px">@include('partials.pic', ['url' => $banner->pictureUrl(), 'alt' => $banner->title, 'h' => 110])</div>
    <label class="row"><input type="checkbox" name="remove_image" value="1" style="width:auto"> Remove current picture</label>
  @endif
  <input type="file" name="image" accept="image/*">
  <label class="row" style="margin-top:10px"><input type="checkbox" name="active" value="1" style="width:auto" @checked(old('active', $banner->active))> Show on the shop page</label>
  <p class="row"><button class="primary">Save banner</button><a class="btn" href="{{ route('admin.banners.index') }}">Cancel</a></p>
</form>
@endsection
