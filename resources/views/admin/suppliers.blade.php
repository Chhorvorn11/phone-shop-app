@extends('layouts.shop')
@section('title', 'Suppliers')
@section('content')
<h1>Suppliers</h1>
<form method="POST" action="{{ route('admin.suppliers.store') }}" class="row panel" style="margin-bottom:16px">@csrf
  <input class="grow" name="name" placeholder="Company name" required>
  <input class="grow" name="contact_name" placeholder="Contact person">
  <input class="grow" name="email" type="email" placeholder="Email">
  <input class="grow" name="phone" placeholder="Phone">
  <button class="primary">Add supplier</button>
</form>
<div class="panel"><table>
  <tr><th>Supplier</th><th>Contact</th><th>Purchases</th><th></th></tr>
  @forelse ($suppliers as $s)
    <tr>
      <td><b>{{ $s->name }}</b>
        <details><summary>Edit</summary>
          <form method="POST" action="{{ route('admin.suppliers.update', $s) }}" class="row">@csrf @method('PUT')
            <input class="grow" name="name" value="{{ $s->name }}" required><input class="grow" name="contact_name" value="{{ $s->contact_name }}">
            <input class="grow" name="email" value="{{ $s->email }}"><input class="grow" name="phone" value="{{ $s->phone }}"><input class="grow" name="address" value="{{ $s->address }}" placeholder="Address">
            <button class="primary">Save supplier</button>
          </form>
        </details></td>
      <td>{{ $s->contact_name }}<div class="mute">{{ $s->email }} {{ $s->phone }}</div></td><td>{{ $s->purchases_count }}</td>
      <td><form class="inline" method="POST" action="{{ route('admin.suppliers.destroy', $s) }}" onsubmit="return confirm('Delete {{ $s->name }} and its purchases?')">@csrf @method('DELETE')<button class="danger">Delete</button></form></td>
    </tr>
  @empty <tr><td colspan="4" class="mute">No suppliers yet.</td></tr> @endforelse
</table></div>
@endsection
