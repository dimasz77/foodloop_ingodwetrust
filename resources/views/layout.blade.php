<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'FoodLoop') – FoodLoop</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']},colors:{cream:'#f7f5f0',teal2:'#1f4b4a',moss:'#1f6b3a'}}}}</script>
<style type="text/tailwindcss">@layer components{.btn{@apply inline-flex items-center justify-center gap-1 rounded-lg bg-teal2 px-4 py-2 text-xs font-bold text-white hover:bg-[#173a39] disabled:opacity-50}.btn-w{@apply bg-white !text-teal2 border border-slate-200 hover:bg-slate-50}.card{@apply rounded-2xl border border-black/5 bg-white p-5 shadow-sm}.inp{@apply w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs focus:border-moss focus:ring-moss}.lbl{@apply mb-1 block text-xs font-bold text-slate-700}.h2{@apply text-lg font-extrabold text-slate-900}}[x-cloak]{display:none!important}</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stack('head')</head>
<body class="bg-cream font-sans text-slate-800 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-50 focus:bg-white focus:p-2">Lewati ke konten</a>
<header class="sticky top-0 z-[1000] px-4 pt-3"><div x-data="{o:false}" @keydown.escape="o=false" class="relative mx-auto flex max-w-5xl items-center justify-between rounded-2xl bg-white px-5 py-2.5 shadow-md ring-1 ring-black/5">
 <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-moss"><span class="text-xl">🌿</span> FoodLoop</a>
 @php $u=auth()->user(); @endphp
 <nav class="flex items-center gap-1 text-xs sm:gap-3" aria-label="Navigasi utama">
  @foreach(['home'=>'Home','map'=>'Map','report.create'=>'Report','impact'=>'Impact'] as $r=>$l)@if($r!='report.create' || !$u || $u->role=='provider')<a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif class="px-1.5 py-1 {{ request()->routeIs($r)?'font-extrabold text-slate-900':'text-slate-500 hover:text-slate-900' }}">{{ $l }}</a>@endif @endforeach
  @auth<button @click="o=!o" :aria-expanded="o" aria-label="Menu akun" class="flex items-center gap-2 rounded-full border border-slate-300 py-1 pl-1 pr-3"><span class="grid h-6 w-6 place-items-center rounded-full bg-teal2 text-[10px] font-bold text-white">{{ strtoupper(substr($u->name,0,1)) }}</span><span class="hidden max-w-[7rem] truncate font-bold sm:block">{{ $u->name }}</span></button>
  @else<a class="px-2 py-1 font-bold text-slate-700" href="{{ route('login') }}">Masuk</a><a class="btn" href="{{ route('register') }}">Daftar</a>@endauth</nav>
 @auth<div x-show="o" x-cloak @click.outside="o=false" class="absolute right-3 top-full mt-2 w-56 rounded-xl bg-white p-2 text-xs shadow-xl ring-1 ring-black/5">
  <div class="border-b px-3 pb-2"><b>{{ $u->name }}</b><br><span class="rounded bg-green-100 px-1.5 py-0.5 text-[10px] font-bold text-green-800">{{ $u->role=='provider'?'Pemberi Makanan':'Penerima Makanan' }}</span></div>
  @if($u->role=='provider')<a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('report.create') }}">➕ Buat Laporan</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('status') }}">📋 Status Laporan</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('history') }}">🕘 Riwayat Kontribusi</a>
  @else<a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('map') }}">📍 Cari &amp; Klaim Makanan</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('status') }}">📦 Klaim Saya</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('history') }}">🕘 Riwayat Penerimaan</a>@endif
  <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50">↪ Keluar</button></form></div>@endauth
</div></header>
<main id="main"><div class="mx-auto max-w-5xl px-4 pt-4">@if(session('ok'))<div role="status" class="rounded-xl bg-green-100 p-3 text-sm font-semibold text-green-900">{{ session('ok') }}</div>@endif
@if($errors->any())<div role="alert" class="mt-2 rounded-xl bg-red-100 p-3 text-sm text-red-900"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif</div>
@yield('content')</main>
<footer class="mt-16 bg-gradient-to-br from-[#1b4747] to-[#31706a] px-4 py-14 text-white"><div class="mx-auto max-w-5xl">
 <div class="text-center"><div class="text-2xl">🌿</div><div class="text-xs">FoodLoop</div><h2 class="mt-3 text-xl font-extrabold">Ready to Rescue Food?</h2><p class="mx-auto mt-2 max-w-md text-xs text-white/80">Become part of the FoodLoop network and help ensure that every meal finds a purpose instead of ending up in landfills.</p>
  <div class="mt-4 flex justify-center gap-3"><a class="btn btn-w" href="{{ route('report.create') }}">Report Waste</a><a class="btn btn-w" href="{{ route('register') }}">Join as Partner</a></div></div>
 <div class="mt-10 grid gap-6 text-xs sm:grid-cols-3"><div><b>Contact Us</b><p class="mt-2 text-white/80">Institut Teknologi Del<br>Laguboti, Sumatera Utara, Indonesia<br>Phone: +62 8129 3859<br>Email: foodloop@example.id</p></div>
 <div><b>Resources</b><p class="mt-2 space-y-1 text-white/80">FAQ<br>Panduan Pengguna<br>Kebijakan Privasi<br>Syarat &amp; Ketentuan</p></div><div><b>Follow Us</b><p class="mt-2 space-y-1 text-white/80">Facebook<br>Youtube<br>Instagram</p></div></div></div></footer>
@stack('scripts')
</body></html>
