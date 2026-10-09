@extends('layouts.shop')
@section('title', 'Messages')
@section('content')
<h1>Customer messages</h1>
@forelse ($messages as $m)
  <div class="panel" style="margin-bottom:10px">
    <div class="row" style="justify-content:space-between">
      <b>{{ $m->subject ?: 'No subject' }}</b>
      <span class="mute">{{ $m->created_at->format('d M Y H:i') }} {!! $m->read_at ? '' : '<span class="badge s-pending">new</span>' !!}</span>
    </div>
    <div class="mute">{{ $m->name }} &lt;{{ $m->email }}&gt;</div>
    <p>{{ $m->message }}</p>
    <div class="row">
      @unless ($m->read_at)<form class="inline" method="POST" action="{{ route('admin.messages.read', $m) }}">@csrf @method('PATCH')<button>Mark as read</button></form>@endunless
      <form class="inline" method="POST" action="{{ route('admin.messages.destroy', $m) }}" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')<button class="danger">Delete</button></form>
    </div>
  </div>
@empty <p class="mute">No messages yet.</p> @endforelse
{{ $messages->links() }}
@endsection
