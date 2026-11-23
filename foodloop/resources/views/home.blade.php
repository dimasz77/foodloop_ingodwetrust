@extends('layout')
@section('title','Beranda')
@section('content')
<section class="hero-glow relative isolate overflow-hidden rounded-[2rem] border border-emerald-100 px-6 py-10 shadow-sm sm:px-10 lg:px-14 lg:py-16">
 <div class="absolute -right-16 -top-20 -z-10 h-72 w-72 rounded-full bg-emerald-200/50 blur-3xl"></div>
 <div class="absolute -bottom-24 left-1/3 -z-10 h-64 w-64 rounded-full bg-orange-200/50 blur-3xl"></div>
 <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_.95fr]">
  <div class="reveal">
   <span class="pill">🌱 SMALL ACTION, BIG IMPACT</span>
   <h1 class="mt-5 max-w-2xl text-4xl font-black leading-[1.08] tracking-tight text-slate-900 sm:text-5xl lg:text-6xl">Makanan berlebih punya <span class="text-emerald-700">kesempatan kedua.</span></h1>
   <p class="mt-5 max-w-xl text-base leading-8 text-slate-600 sm:text-lg">FoodLoop menghubungkan penyedia makanan dengan penerima manfaat dan mitra pengolah organik. Selamatkan makanan sebelum terbuang, mulai dari sekitar kita.</p>
   <div class="mt-8 flex flex-wrap gap-3">
    <a class="btn" href="{{ route('report.create') }}">Laporkan Makanan <span aria-hidden="true">→</span></a>
    <a class="btn btn-o" href="{{ route('map') }}">Jelajahi Rescue Map</a>
   </div>
   <div class="mt-7 flex flex-wrap gap-x-6 gap-y-2 text-sm font-semibold text-slate-500"><span>✓ Mudah dilaporkan</span><span>✓ Terhubung dengan mitra</span><span>✓ Dampak terukur</span></div>
  </div>
  <div class="reveal relative mx-auto w-full max-w-lg">
   <div class="absolute -left-3 top-8 z-10 rounded-2xl border border-white bg-white/95 p-3 shadow-xl sm:-left-7"><span class="text-2xl">🥗</span><span class="ml-2 text-sm font-bold text-slate-700">Food rescued</span></div>
   <div class="absolute -right-2 bottom-8 z-10 rounded-2xl border border-white bg-white/95 p-3 shadow-xl sm:-right-5"><span class="text-2xl">🌍</span><span class="ml-2 text-sm font-bold text-slate-700">Less food waste</span></div>
   <div class="relative grid aspect-[5/4] place-items-center overflow-hidden rounded-[2rem] border-8 border-white bg-gradient-to-br from-emerald-100 via-lime-50 to-orange-100 shadow-2xl">
    <div class="absolute inset-0 opacity-30" style="background-image:radial-gradient(#059669 1px,transparent 1px);background-size:18px 18px"></div>
    <div class="relative text-center">
     <div class="mx-auto grid h-36 w-36 place-items-center rounded-full bg-white/80 text-7xl shadow-xl ring-8 ring-white/40 sm:h-44 sm:w-44 sm:text-8xl">🥬</div>
     <p class="mt-5 text-2xl font-black tracking-tight text-emerald-950">Rescue. Share. Regrow.</p>
     <p class="mt-1 text-sm font-medium text-emerald-800">A better loop for our food.</p>
    </div>
    <span class="absolute left-8 top-24 text-4xl">🍅</span><span class="absolute bottom-14 left-10 text-4xl">🥕</span><span class="absolute right-10 top-12 text-4xl">🍋</span><span class="absolute bottom-20 right-12 text-4xl">🌿</span>
   </div>
  </div>
 </div>
</section>

<section class="reveal mt-8">
 <div class="mb-4 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-black uppercase tracking-[.2em] text-emerald-700">Our collective impact</p><h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">Dampak yang sudah kita buat</h2></div><a href="{{ route('impact') }}" class="text-sm font-bold text-emerald-700 hover:underline">Lihat detail dampak →</a></div>
 @include('_stats')
</section>

<section class="reveal mt-16">
 <div class="max-w-2xl"><p class="text-xs font-black uppercase tracking-[.2em] text-emerald-700">How it works</p><h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Satu alur sederhana, dampak yang berarti.</h2><p class="mt-3 leading-7 text-slate-600">Setiap laporan akan diarahkan ke jalur penyaluran yang sesuai dengan kondisi makanan.</p></div>
 <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  @foreach([['01','📋','Laporkan','Penyedia mengisi informasi makanan berlebih dan kondisi penyimpanannya.'],['02','🔎','Temukan','Relawan atau penerima manfaat menemukan makanan melalui peta.'],['03','🛵','Jemput','Makanan diklaim dan dijemput sebelum batas waktu.'],['04','💚','Salurkan','Makanan diserahkan atau sisa organik diproses dengan tepat.']] as [$n,$i,$t,$d])
  <article class="card"><span class="text-xs font-black tracking-widest text-emerald-700">STEP {{ $n }}</span><div class="my-4 grid h-14 w-14 place-items-center rounded-2xl bg-emerald-50 text-3xl">{{ $i }}</div><h3 class="text-lg font-extrabold text-slate-900">{{ $t }}</h3><p class="mt-2 text-sm leading-6 text-slate-600">{{ $d }}</p></article>
  @endforeach
 </div>
</section>

<section class="reveal mt-16 grid gap-5 lg:grid-cols-2">
 <article class="relative overflow-hidden rounded-[2rem] bg-emerald-900 p-8 text-white sm:p-10"><span class="text-xs font-black uppercase tracking-[.2em] text-emerald-200">Human-first</span><div class="mt-4 text-5xl">🍱</div><h2 class="mt-4 text-2xl font-black">Makanan layak, untuk yang membutuhkan.</h2><p class="mt-3 max-w-lg leading-7 text-emerald-50/80">Makanan yang masih aman dikonsumsi diarahkan kepada penerima manfaat agar dapat memberikan nilai lebih.</p></article>
 <article class="relative overflow-hidden rounded-[2rem] bg-orange-100 p-8 text-orange-950 sm:p-10"><span class="text-xs font-black uppercase tracking-[.2em] text-orange-800">Earth-first</span><div class="mt-4 text-5xl">♻️</div><h2 class="mt-4 text-2xl font-black">Sisa organik, kembali menjadi sumber daya.</h2><p class="mt-3 max-w-lg leading-7 text-orange-950/75">Makanan yang tidak layak konsumsi tidak disalurkan untuk dimakan, tetapi diarahkan ke pengolahan organik seperti kompos atau maggot BSF.</p></article>
</section>

<section class="reveal mt-16 rounded-[2rem] border border-emerald-100 bg-white px-6 py-10 text-center shadow-sm sm:px-10">
 <p class="text-xs font-black uppercase tracking-[.2em] text-emerald-700">Be part of the loop</p><h2 class="mx-auto mt-3 max-w-2xl text-3xl font-black tracking-tight text-slate-900">Jangan biarkan makanan berakhir sia-sia.</h2><p class="mx-auto mt-3 max-w-xl leading-7 text-slate-600">Mulai dari satu laporan, satu penjemputan, dan satu tindakan kecil yang membawa perubahan.</p><div class="mt-7 flex flex-wrap justify-center gap-3"><a class="btn" href="{{ route('report.create') }}">Mulai Berkontribusi →</a><a class="btn btn-o" href="{{ route('impact') }}">Lihat Impact</a></div>
</section>
@endsection
