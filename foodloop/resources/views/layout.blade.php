<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'FoodLoop') – FoodLoop</title><meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.tailwindcss.com"></script>
<style type="text/tailwindcss">@layer components{
.btn{@apply inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-900/10 transition duration-200 hover:-translate-y-0.5 hover:bg-emerald-800 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50}
.btn-o{@apply border border-emerald-200 bg-white !text-emerald-900 shadow-sm hover:border-emerald-400 hover:bg-emerald-50}
.card{@apply rounded-3xl border border-stone-200/80 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl}
.inp{@apply w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100}
.lbl{@apply mb-1.5 block text-sm font-bold text-slate-700}
}
:root{scroll-behavior:smooth}
body{background:linear-gradient(180deg,#fbfaf6 0%,#f7faf7 55%,#fff 100%)}
.nav-link{position:relative}
.nav-link:after{content:"";position:absolute;left:12px;right:12px;bottom:5px;height:2px;border-radius:9px;background:#059669;transform:scaleX(0);transform-origin:left;transition:transform .2s}
.nav-link:hover:after,.nav-link[aria-current="page"]:after{transform:scaleX(1)}
.hero-glow{background:radial-gradient(circle at 70% 35%,rgba(167,243,208,.55),transparent 42%),linear-gradient(135deg,#f0fdf4,#fff7ed)}
.reveal{opacity:0;transform:translateY(22px);transition:opacity .65s ease,transform .65s ease}
.reveal.is-visible{opacity:1;transform:translateY(0)}
.stat-card{background:linear-gradient(145deg,#fff,#f0fdf4)}
.pill{display:inline-flex;align-items:center;gap:.4rem;border:1px solid #d1fae5;border-radius:999px;background:#ecfdf5;padding:.4rem .8rem;color:#065f46;font-size:.75rem;font-weight:800}

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stack('head')</head>
<body class="bg-stone-50 text-slate-800 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-50 focus:bg-white focus:p-2">Lewati ke konten</a>
<header x-data="{o:false}" class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
 <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
  <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-black tracking-tight text-emerald-900"><span class="grid h-10 w-10 place-items-center rounded-2xl bg-emerald-700 text-xl text-white shadow-lg shadow-emerald-900/20">♻</span>FoodLoop<span class="ml-1 hidden rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-extrabold uppercase tracking-widest text-emerald-700 sm:inline">Rescue food</span></a>
  <nav class="hidden gap-1 md:flex" aria-label="Navigasi utama">
   @foreach(['home'=>'Home','map'=>'Map','report.create'=>'Report','status'=>'Status','impact'=>'Impact'] as $r=>$l)
   <a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif class="nav-link rounded-lg px-3 py-2 text-sm font-semibold {{ request()->routeIs($r) ? 'bg-emerald-50 text-emerald-800' : 'text-slate-600 hover:bg-slate-100' }}">{{ $l }}</a>
   @endforeach
  </nav>
  <button class="md:hidden" @click="o=!o" :aria-expanded="o" aria-label="Menu">☰</button>
 </div>
 <nav x-show="o" x-cloak class="border-t px-4 py-2 md:hidden">
  @foreach(['home'=>'Home','map'=>'Map','report.create'=>'Report','status'=>'Status','impact'=>'Impact'] as $r=>$l)<a class="block py-2" href="{{ route($r) }}">{{ $l }}</a>@endforeach
 </nav>
</header>
<main id="main" class="mx-auto min-h-[65vh] max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
 @if(session('ok'))<div role="status" class="mb-4 rounded-lg bg-emerald-100 p-3 text-emerald-900">{{ session('ok') }}</div>@endif
 @if($errors->any())<div role="alert" class="mb-4 rounded-lg bg-red-100 p-3 text-red-900"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
 @yield('content')
</main>
<footer class="mt-16 bg-[#102c25] py-10 text-sm text-emerald-50/75"><div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 md:grid-cols-3 lg:px-8">
 <div><b class="text-xl font-black text-white">♻ FoodLoop</b><p class="mt-3 max-w-sm leading-7">Menyelamatkan makanan berlebih, menghubungkan kebaikan, dan mengurangi sampah organik untuk masa depan yang lebih berkelanjutan.</p></div>
 <div><b class="text-white">Kontak</b><p>Institut Teknologi Del, Laguboti, Sumatera Utara<br>foodloop@example.id</p></div>
 <div><b class="text-white">Menu</b><p><a href="{{ route('map') }}">Peta</a> · <a href="{{ route('report.create') }}">Lapor</a> · <a href="{{ route('impact') }}">Dampak</a></p></div>
</div></footer>
<style>[x-cloak]{display:none!important}</style>
<script>
(function(){
 const bar=document.createElement('div');
 bar.style.cssText='position:fixed;top:0;left:0;height:3px;width:0;background:#10b981;z-index:100;transition:width .08s';
 document.body.appendChild(bar);
 const update=()=>{const h=document.documentElement.scrollHeight-innerHeight;bar.style.width=(h>0?(scrollY/h)*100:0)+'%'};
 addEventListener('scroll',update,{passive:true});update();
 const nodes=document.querySelectorAll('.reveal');
 if('IntersectionObserver' in window){
   const io=new IntersectionObserver(items=>items.forEach(item=>{if(item.isIntersecting){item.target.classList.add('is-visible');io.unobserve(item.target)}}),{threshold:.12});
   nodes.forEach(node=>io.observe(node));
 }else{nodes.forEach(node=>node.classList.add('is-visible'))}
})();
</script>
@stack('scripts')
</body></html>
