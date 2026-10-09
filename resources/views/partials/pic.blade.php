@if (! empty($url))
  <img src="{{ $url }}" alt="{{ $alt ?? '' }}" style="width:{{ $w ?? '100%' }};height:{{ $h ?? 130 }}px;object-fit:cover;border-radius:8px;display:block">
@else
  <div style="width:{{ $w ?? '100%' }};height:{{ $h ?? 130 }}px;border-radius:8px;background:var(--bg);display:grid;place-items:center;color:var(--mute);font-size:12px">No picture</div>
@endif
