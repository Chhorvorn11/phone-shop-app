@extends('layouts.shop')
@section('title', 'Receive stock')
@section('content')
<h1>Receive stock</h1>
<form method="POST" action="{{ route('admin.purchases.store') }}" class="panel" style="max-width:640px">@csrf
  <label>Supplier</label>
  <select name="supplier_id" required>@foreach ($suppliers as $s)<option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>@endforeach</select>
  <label>Reference (invoice or PO number)</label><input name="reference" value="{{ old('reference') }}">
  <label>Date received</label><input type="date" name="purchased_at" value="{{ old('purchased_at', now()->toDateString()) }}" required>
  <label>Phones received (leave unused rows empty)</label>
  @for ($i = 0; $i < 3; $i++)
    <div class="row" style="margin-bottom:6px">
      <select class="grow" name="items[{{ $i }}][phone_id]"><option value="">Phone</option>
        @foreach ($phones as $ph)<option value="{{ $ph->id }}" @selected(old("items.$i.phone_id") == $ph->id)>{{ $ph->brand }} {{ $ph->name }}</option>@endforeach
      </select>
      <input name="items[{{ $i }}][quantity]" type="number" min="1" placeholder="Qty" style="width:90px" value="{{ old("items.$i.quantity") }}">
      <input name="items[{{ $i }}][unit_cost]" type="number" min="0" placeholder="Unit cost" style="width:120px" value="{{ old("items.$i.unit_cost") }}">
    </div>
  @endfor
  <label>Note</label><input name="note" value="{{ old('note') }}">
  <p class="row"><button class="primary">Receive stock</button><a class="btn" href="{{ route('admin.purchases.index') }}">Cancel</a></p>
</form>
@endsection
