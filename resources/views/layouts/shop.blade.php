<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Phone Store')</title>
<style>
:root{--bg:#f4f5f8;--panel:#fff;--ink:#141821;--mute:#66708a;--line:#e1e4ec;--accent:#2b4bff;--danger:#d63b3b;--ok:#1f8a5b}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font:15px/1.5 system-ui,sans-serif}
a{color:var(--accent);text-decoration:none}
nav{background:var(--panel);border-bottom:1px solid var(--line);padding:10px 16px;display:flex;flex-wrap:wrap;gap:14px;align-items:center}
nav .brand{font-weight:800;font-size:18px;color:var(--ink);margin-right:8px}nav .sp{flex:1}.subnav{background:var(--bg);font-size:14px;padding:8px 16px}
main{max-width:1040px;margin:0 auto;padding:20px 16px 48px}
h1{font-size:26px;margin:0 0 16px}h2{font-size:18px;margin:24px 0 10px}
.panel{background:var(--panel);border:1px solid var(--line);border-radius:12px;padding:16px;overflow-x:auto}
table{width:100%;border-collapse:collapse;text-align:left}th,td{padding:9px 10px;border-top:1px solid var(--line)}th{border-top:0;color:var(--mute);font-size:13px}
input,select,textarea,button{font:inherit;padding:8px 10px;border:1px solid var(--line);border-radius:8px;background:var(--panel);color:var(--ink)}
input,select,textarea{width:100%}button,.btn{cursor:pointer;display:inline-block;background:var(--panel)}
.btn{padding:8px 14px;border:1px solid var(--line);border-radius:8px;color:var(--ink)}
.primary{background:var(--accent)!important;color:#fff!important;border-color:var(--accent)!important}
.danger{color:var(--danger)}
.row{display:flex;gap:8px;flex-wrap:wrap;align-items:center}.row>*{width:auto}.row .grow{flex:1}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:8px}
.stat b{display:block;font-size:26px}.stat span,.mute{color:var(--mute);font-size:13px}
.flash{padding:10px 14px;border-radius:8px;margin-bottom:14px;background:#dff5e9;color:#145c3d}
.errors{background:#fde4e4;color:#8f2020}.errors ul{margin:0;padding-left:18px}
.badge{padding:2px 9px;border-radius:99px;font-size:12px;font-weight:600;background:var(--line)}
.s-pending{background:#fff0c9}.s-confirmed{background:#d9e4ff}.s-completed{background:#dff5e9}.s-cancelled{background:#fde4e4}
.low{color:var(--danger);font-weight:600}
form.inline{display:inline}label{display:block;font-size:13px;color:var(--mute);margin:8px 0 3px}
details summary{cursor:pointer;color:var(--accent)}
</style>
</head>
<body>
<nav>
  <a class="brand" href="{{ route('home') }}">Phone Store</a>
  <a href="{{ route('home') }}">Shop</a>
  <a href="{{ route('accessories') }}">Accessories</a>
  <a href="{{ route('contact') }}">Contact</a>
  @auth
    <a href="{{ route('bookings.mine') }}">My bookings</a>
    <a href="{{ route('wishlist') }}">Wishlist</a>
    <a href="{{ route('profile') }}">Profile</a>
    <span class="sp"></span><span class="mute">{{ auth()->user()->name }}</span>
    <form class="inline" method="POST" action="{{ route('logout') }}">@csrf<button>Sign out</button></form>
  @else
    <span class="sp"></span><a href="{{ route('login') }}">Sign in</a><a href="{{ route('register') }}">Register</a>
  @endauth
</nav>
@if (auth()->check() && auth()->user()->role === 'admin')
<nav class="subnav">
  <b class="mute">Admin</b>
  <a href="{{ route('admin.dashboard') }}">Dashboard</a>
  <a href="{{ route('admin.bookings') }}">Daily bookings</a>
  <a href="{{ route('admin.payments') }}">Payments</a>
  <a href="{{ route('admin.stock') }}">Stock</a>
  <a href="{{ route('admin.movements') }}">Stock log</a>
  <a href="{{ route('admin.purchases.index') }}">Purchases</a>
  <a href="{{ route('admin.suppliers.index') }}">Suppliers</a>
  <a href="{{ route('admin.categories.index') }}">Categories</a>
  <a href="{{ route('admin.coupons.index') }}">Coupons</a>
  <a href="{{ route('admin.accessories.index') }}">Accessories</a>
  <a href="{{ route('admin.banners.index') }}">Banners</a>
  <a href="{{ route('admin.messages') }}">Messages</a>
  <a href="{{ route('admin.reports') }}">Reports</a>
</nav>
@endif
<main>
  @if (session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
  @if ($errors->any())<div class="flash errors"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @yield('content')
</main>
</body>
</html>
