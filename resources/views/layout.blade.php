<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'FoodLoop') – FoodLoop</title><meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.tailwindcss.com"></script>
<style type="text/tailwindcss">@layer components{.btn{@apply inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800 disabled:opacity-50}.btn-o{@apply border border-slate-300 bg-white !text-slate-800 hover:bg-slate-50}.card{@apply rounded-2xl border border-slate-200 bg-white p-5 shadow-sm}.inp{@apply w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-600 focus:ring-emerald-600}.lbl{@apply mb-1 block text-sm font-semibold text-slate-700}}</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stack('head')</head>
<body class="bg-stone-50 text-slate-800 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-50 focus:bg-white focus:p-2">Lewati ke konten</a>
<header x-data="{o:false}" class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
 <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
  <a href="{{ route('home') }}" class="text-lg font-extrabold text-emerald-700">🌿 FoodLoop</a>
  <nav class="hidden gap-1 md:flex" aria-label="Navigasi utama">
   @foreach(['home'=>'Home','map'=>'Map','report.create'=>'Report','status'=>'Status','impact'=>'Impact'] as $r=>$l)
   <a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif class="rounded-lg px-3 py-2 text-sm {{ request()->routeIs($r) ? 'bg-emerald-50 font-bold text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">{{ $l }}</a>
   @endforeach
  </nav>
  <button class="md:hidden" @click="o=!o" :aria-expanded="o" aria-label="Menu">☰</button>
 </div>
 <nav x-show="o" x-cloak class="border-t px-4 py-2 md:hidden">
  @foreach(['home'=>'Home','map'=>'Map','report.create'=>'Report','status'=>'Status','impact'=>'Impact'] as $r=>$l)<a class="block py-2" href="{{ route($r) }}">{{ $l }}</a>@endforeach
 </nav>
</header>
<main id="main" class="mx-auto max-w-6xl px-4 py-8">
 @if(session('ok'))<div role="status" class="mb-4 rounded-lg bg-emerald-100 p-3 text-emerald-900">{{ session('ok') }}</div>@endif
 @if($errors->any())<div role="alert" class="mb-4 rounded-lg bg-red-100 p-3 text-red-900"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
 @yield('content')
</main>
<footer class="mt-10 bg-slate-900 py-8 text-sm text-slate-300"><div class="mx-auto grid max-w-6xl gap-6 px-4 md:grid-cols-3">
 <div><b class="text-white">FoodLoop</b><p>Platform logistik food rescue dan pengalihan sampah organik. Mendukung SDG 2, 11, 12, dan 13.</p></div>
 <div><b class="text-white">Kontak</b><p>Institut Teknologi Del, Laguboti, Sumatera Utara<br>foodloop@example.id</p></div>
 <div><b class="text-white">Menu</b><p><a href="{{ route('map') }}">Peta</a> · <a href="{{ route('report.create') }}">Lapor</a> · <a href="{{ route('impact') }}">Dampak</a></p></div>
</div></footer>
<style>[x-cloak]{display:none!important}</style>
@stack('scripts')
</body></html>
