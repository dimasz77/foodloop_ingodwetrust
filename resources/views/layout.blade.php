<!doctype html>
<html lang="id" class="scroll-smooth"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'FoodLoop') – FoodLoop</title>
<script>document.documentElement.classList.add('js')</script>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif']},colors:{cream:'#f7f5f0',teal2:'#1f4b4a',moss:'#1f6b3a'},
 keyframes:{float:{'0%,100%':{transform:'translateY(0)'},'50%':{transform:'translateY(-10px)'}},blob:{'0%,100%':{borderRadius:'42% 58% 63% 37% / 41% 44% 56% 59%'},'50%':{borderRadius:'58% 42% 38% 62% / 55% 36% 64% 45%'}},ring:{'0%':{transform:'scale(.6)',opacity:'.8'},'100%':{transform:'scale(2.2)',opacity:'0'}},pop:{'0%':{transform:'scale(.9)',opacity:'0'},'100%':{transform:'scale(1)',opacity:'1'}},
  kenburns:{'0%':{transform:'scale(1.12)'},'100%':{transform:'scale(1)'}},rise:{'0%':{transform:'translateY(24px)',opacity:'0'},'100%':{transform:'none',opacity:'1'}},scrollcue:{'0%':{transform:'translateY(-100%)'},'100%':{transform:'translateY(300%)'}}},
 animation:{float:'float 5s ease-in-out infinite',blob:'blob 9s ease-in-out infinite',ring:'ring 1.6s ease-out infinite',pop:'pop .35s ease-out',kenburns:'kenburns 18s ease-out forwards',rise:'rise .8s cubic-bezier(.2,.7,.2,1) both'}}}}</script>
<style type="text/tailwindcss">@layer components{.btn{@apply inline-flex min-h-[40px] items-center justify-center gap-1.5 rounded-lg bg-[#0c1f19] px-4 py-2 text-xs font-extrabold uppercase tracking-wide text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#143a2c] hover:shadow-md active:translate-y-0 disabled:pointer-events-none disabled:opacity-50}.btn-w{@apply bg-white !text-[#0c1f19] border border-slate-200 hover:bg-slate-50}.btn-g{@apply bg-moss hover:bg-[#185730]}.btn-lime{@apply bg-lime-300 !text-[#0c1f19] hover:bg-lime-200}.card{@apply rounded-2xl border border-black/5 bg-white p-5 shadow-sm}.card-h{@apply transition duration-300 hover:-translate-y-1 hover:shadow-lg}.inp{@apply w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm transition placeholder:text-slate-400 focus:border-moss focus:outline-none focus:ring-2 focus:ring-moss/20 aria-[invalid=true]:border-red-400 aria-[invalid=true]:bg-red-50/40}.err{@apply mt-1 flex items-center gap-1 text-[11px] font-semibold text-red-600}.hint{@apply mt-1 text-[11px] text-slate-500}.lbl{@apply mb-1 block text-xs font-bold text-slate-700}.pull-up{@apply relative z-10 -mt-16 pb-20}.sec{@apply flex items-center gap-3 text-[11px] font-extrabold uppercase tracking-[.2em] text-moss}.h2{@apply text-lg font-extrabold text-slate-900}.chip{@apply inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold}}
[x-cloak]{display:none!important}
:focus-visible{outline:3px solid #84cc16;outline-offset:2px;border-radius:6px}
kbd{font:600 10px ui-monospace,monospace;padding:1px 5px;border-radius:4px;border:1px solid #cbd5e1;border-bottom-width:2px;background:#fff;color:#334155}
.js [data-reveal]{opacity:0;transform:translateY(18px);transition:opacity .6s ease,transform .6s ease}
.js [data-reveal].in{opacity:1;transform:none}
@media (prefers-reduced-motion:reduce){[data-reveal]{opacity:1;transform:none;transition:none}*{animation:none!important;scroll-behavior:auto!important}}</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stack('head')</head>
<body class="bg-cream font-sans text-slate-800 antialiased">
@php
  $u = auth()->user();
  $isProvider = $u?->role === 'provider';
  $nav = ['home' => ['Beranda', '🏠'], 'map' => ['Peta', '📍'], 'report.create' => ['Lapor', '➕'], 'impact' => ['Dampak', '🌍']];
@endphp
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-50 focus:bg-white focus:p-2">Lewati ke konten</a>
<header class="sticky top-0 z-[1000] bg-[#0c1f19] text-white transition-shadow duration-300" :class="s&&'shadow-xl shadow-black/20'" x-data="{o:false,m:false,s:false}" @scroll.window="s=window.scrollY>10" @keydown.escape="o=false;m=false">
 <div class="relative mx-auto flex h-[4.5rem] max-w-6xl items-center justify-between px-4">
  <a href="{{ route('home') }}" class="transition hover:opacity-80" aria-label="FoodLoop – Beranda">@include('_logo', ['light' => true, 'size' => 34])</a>
  <nav class="hidden h-full items-stretch gap-7 text-[13px] font-extrabold uppercase tracking-wide md:flex" aria-label="Navigasi utama">
   @foreach($nav as $r => [$l, $i])
    @if($r != 'report.create' || !$u || $isProvider)
     <a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif class="relative flex items-center transition {{ request()->routeIs($r) ? 'text-lime-300' : 'text-white/90 hover:text-lime-300' }}">{{ $l }}<span class="absolute inset-x-0 bottom-0 h-[3px] rounded-t bg-lime-300 transition-transform duration-300 {{ request()->routeIs($r) ? 'scale-x-100' : 'scale-x-0' }}"></span></a>
    @endif
   @endforeach
  </nav>
  <div class="flex items-center gap-2 text-xs">
   @auth
    <button @click="o=!o" :aria-expanded="o" aria-label="Menu akun" class="flex items-center gap-2 rounded-full bg-white/10 py-1 pl-1 pr-3 ring-1 ring-white/15 transition hover:bg-white/15"><span class="grid h-7 w-7 place-items-center rounded-full bg-lime-300 text-[11px] font-extrabold text-[#0c1f19]">{{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}</span><span class="hidden max-w-[7rem] truncate font-bold sm:block">{{ $u->name }}</span><svg class="h-3 w-3 transition" :class="o&&'rotate-180'" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 4.5l3 3 3-3"/></svg></button>
   @else
    <a class="hidden px-2 py-1 font-extrabold uppercase tracking-wide text-white/90 hover:text-lime-300 sm:block" href="{{ route('login') }}">Masuk</a><a class="inline-flex items-center rounded-lg bg-lime-300 px-4 py-2 font-extrabold uppercase tracking-wide text-[#0c1f19] transition hover:bg-lime-200" href="{{ route('register') }}">Daftar</a>
   @endauth
   <button class="grid h-9 w-9 place-items-center rounded-lg text-lg hover:bg-white/10 md:hidden" @click="m=!m" :aria-expanded="m" aria-label="Buka menu navigasi"><span x-text="m?'✕':'☰'">☰</span></button>
  </div>
  @auth
  <div x-show="o" x-cloak x-transition.origin.top.right @click.outside="o=false" class="absolute right-4 top-full mt-2 w-60 rounded-xl bg-white p-2 text-xs text-slate-800 shadow-xl ring-1 ring-black/5">
   <div class="border-b px-3 pb-2"><b>{{ $u->name }}</b><div class="text-[10px] text-slate-500">{{ $u->organization }}</div><span class="chip mt-1 bg-green-100 text-green-800">{{ $isProvider ? 'Pemberi Makanan' : 'Penerima Makanan' }}</span></div>
   @if($isProvider)
    <a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('report.create') }}">Buat Laporan</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('status') }}">Status Laporan</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('history') }}">Riwayat &amp; Lencana</a>
   @else
    <a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('map') }}">Cari &amp; Klaim Makanan</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('status') }}">Klaim Saya</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('history') }}">Riwayat &amp; Lencana</a>
   @endif
   <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50">Keluar</button></form>
  </div>
  @endauth
 </div>
 <nav x-show="m" x-cloak x-transition @click.outside="m=false" class="border-t border-white/10 px-4 pb-4 pt-2 text-sm font-extrabold uppercase tracking-wide md:hidden" aria-label="Navigasi seluler">
  @foreach($nav as $r => [$l, $i])
   @if($r != 'report.create' || !$u || $isProvider)
    <a href="{{ route($r) }}" class="block border-l-[3px] px-3 py-3 {{ request()->routeIs($r) ? 'border-lime-300 text-lime-300' : 'border-transparent text-white/90' }}">{{ $l }}</a>
   @endif
  @endforeach
  @guest<a href="{{ route('login') }}" class="block border-l-[3px] border-transparent px-3 py-3 text-white/90">Masuk</a>@endguest
 </nav>
</header>

@if(session('ok'))
<div x-data="{v:true}" x-init="setTimeout(()=>v=false,5500)" x-show="v" x-transition.opacity.duration.400ms role="status" class="fixed bottom-5 left-1/2 z-[2000] w-[calc(100%-2rem)] max-w-md -translate-x-1/2">
 <div class="flex animate-pop items-start gap-3 rounded-2xl bg-teal2 p-4 text-sm text-white shadow-2xl"><span class="text-lg">✅</span><p class="flex-1 font-semibold">{{ session('ok') }}</p><button @click="v=false" class="text-white/70 hover:text-white" aria-label="Tutup notifikasi">✕</button></div>
 <div class="mx-4 h-1 origin-left rounded-b bg-lime-300" style="animation:shrink 5.5s linear forwards"></div>
</div>
<style>@keyframes shrink{from{transform:scaleX(1)}to{transform:scaleX(0)}}</style>
@endif

<main id="main">
 @if($errors->any())<div class="mx-auto max-w-6xl px-4 pt-4"><div role="alert" class="animate-pop rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-900"><b>Ada yang perlu diperbaiki:</b><ul class="mt-1 list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div></div>@endif
 @yield('content')
</main>

<footer class="relative overflow-hidden border-t border-white/10 bg-[#0c1f19] px-4 py-14 text-white">
 <x-contour class="absolute inset-0 h-full w-full opacity-[.06]" />
 <div class="relative mx-auto max-w-6xl">
  <div class="text-center" data-reveal><div class="flex justify-center">@include('_logo', ['light' => true, 'size' => 40])</div><h2 class="mt-3 text-2xl font-extrabold">Siap Menyelamatkan Makanan?</h2><p class="mx-auto mt-2 max-w-md text-xs text-white/80">Jadilah bagian dari jaringan FoodLoop dan pastikan setiap makanan menemukan tujuannya, bukan berakhir di TPA.</p>
   <div class="mt-5 flex flex-wrap justify-center gap-3">
    @if($isProvider)<a class="btn btn-w" href="{{ route('report.create') }}">➕ Laporkan Surplus</a><a class="btn btn-w" href="{{ route('history') }}">🏅 Lencana Saya</a>
    @elseif($u)<a class="btn btn-w" href="{{ route('map') }}">📍 Cari Makanan Terdekat</a><a class="btn btn-w" href="{{ route('history') }}">🏅 Lencana Saya</a>
    @else<a class="btn btn-w" href="{{ route('register', ['role' => 'provider']) }}">🍽️ Daftar sebagai Pemberi</a><a class="btn btn-w" href="{{ route('register', ['role' => 'recipient']) }}">🤝 Daftar sebagai Penerima</a>@endif
   </div></div>
  <div class="mt-12 grid gap-6 border-t border-white/15 pt-8 text-xs sm:grid-cols-3">
   <div><b>Hubungi Kami</b><p class="mt-2 text-white/80">Institut Teknologi Del<br>Laguboti, Sumatera Utara, Indonesia<br>Telepon: +62 8129 3859<br>Email: foodloop@example.id</p></div>
   <div><b>Jelajahi</b><p class="mt-2 flex flex-col gap-1 text-white/80"><a class="hover:text-white" href="{{ route('map') }}">Peta Penyelamatan</a><a class="hover:text-white" href="{{ route('impact') }}">Dampak Komunitas</a><a class="hover:text-white" href="{{ route('home') }}#faq">FAQ</a></p></div>
   <div><b>Ikuti Kami</b><p class="mt-2 space-y-1 text-white/80">Facebook<br>Youtube<br>Instagram</p></div>
  </div>
  <p class="mt-8 text-center text-[10px] text-white/50">© {{ date('Y') }} FoodLoop · Angka dampak adalah estimasi.</p>
 </div>
</footer>

<button x-data="{v:false}" @scroll.window="v=window.scrollY>600" x-show="v" x-cloak x-transition @click="window.scrollTo({top:0,behavior:'smooth'})" class="fixed bottom-5 right-5 z-[1500] grid h-11 w-11 place-items-center rounded-full bg-moss text-white shadow-lg transition hover:-translate-y-1" aria-label="Kembali ke atas">↑</button>

<script>
/* Animasi muncul saat di-scroll & angka berjalan (count-up) */
(()=>{
 const fmt=(v,d)=>v.toLocaleString('id-ID',{minimumFractionDigits:d,maximumFractionDigits:d});
 const count=el=>{const to=parseFloat(el.dataset.count)||0,d=+(el.dataset.decimals||0),t0=performance.now(),dur=1400;
  const step=t=>{const p=Math.min(1,(t-t0)/dur),e=1-Math.pow(1-p,3);el.textContent=fmt(to*e,d);p<1&&requestAnimationFrame(step)};requestAnimationFrame(step)};
 const io=new IntersectionObserver(es=>es.forEach(e=>{if(!e.isIntersecting)return;e.target.classList.add('in');e.target.querySelectorAll('[data-count]').forEach(count);e.target.dataset.count!==undefined&&count(e.target);io.unobserve(e.target)}),{threshold:.15});
 const init=()=>document.querySelectorAll('[data-reveal],[data-count]:not([data-reveal] [data-count])').forEach(el=>io.observe(el));
 document.readyState==='loading'?document.addEventListener('DOMContentLoaded',init):init();
 /* Hitung mundur umum: elemen dengan data-exp */
 window.hms=ms=>{let s=Math.max(0,ms)/1000|0;return [s/3600|0,s%3600/60|0,s%60].map(v=>String(v).padStart(2,'0')).join(':')};
 setInterval(()=>document.querySelectorAll('[data-exp]').forEach(n=>{const ms=new Date(n.dataset.exp)-Date.now();n.textContent=ms>0?hms(ms):'Habis'}),1000);
})();
</script>
@stack('scripts')
</body></html>
