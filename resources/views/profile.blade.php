@extends('layouts.shop')
@section('title', 'Profile')
@section('content')
<h1>My profile</h1>
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="panel" style="max-width:420px">@csrf @method('PUT')
  <div style="width:96px">@include('partials.pic', ['url' => $p->pictureUrl(), 'alt' => 'Profile picture', 'h' => 96])</div>
  <label>Profile picture (JPG, PNG or WEBP, up to 2 MB)</label>
  <input type="file" name="avatar" accept="image/*">
  @if ($p->avatar)<label class="row"><input type="checkbox" name="remove_avatar" value="1" style="width:auto"> Remove picture</label>@endif
  <label>Name</label><input value="{{ auth()->user()->name }}" disabled>
  <label>Phone number</label><input name="phone_number" value="{{ old('phone_number', $p->phone_number) }}">
  <label>Address</label><input name="address" value="{{ old('address', $p->address) }}">
  <label>City</label><input name="city" value="{{ old('city', $p->city) }}">
  <p><button class="primary">Save profile</button></p>
</form>
@endsection
