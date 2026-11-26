@extends('layout')
@section('title','Beranda')
@section('content')
@php
  $role = auth()->user()?->role;
  $K = \App\Services\ImpactCalculator::KG_PER_PORTION;
  $C = \App\Services\ImpactCalculator::CO2E_PER_KG;
  $CAR = \App\Services\ImpactCalculator::CO2E_PER_CAR_KM;
  $TREE = \App\Services\ImpactCalculator::CO2E_PER_TREE_YEAR;
@endphp

{{-- HERO: latar video/foto. Taruh public/videos/hero.mp4 (opsional .webm) dan/atau public/images/hero.jpg. --}}
<section class="relative isolate flex min-h-[calc(100svh-4.5rem)] items-center overflow-hidden bg-[#0c1f19]">
 <x-bg-media name="hero" focus="35% 40%" kenburns />
 <div class="absolute inset-0 -z-10 bg-[#0c1f19]/70 md:bg-transparent md:bg-gradient-to-r md:from-[#0c1f19]/95 md:via-[#0c1f19]/70 md:via-45% md:to-[#0c1f19]/10"></div>
 <div class="absolute inset-x-0 bottom-0 -z-10 h-40 bg-gradient-to-t from-[#0c1f19]/70 to-transparent"></div>

 <div class="mx-auto w-full max-w-6xl px-4 py-16">
  <h1 class="max-w-3xl animate-rise text-[2.7rem] font-extrabold leading-[1.04] tracking-[-0.02em] text-white drop-shadow-[0_2px_12px_rgba(0,0,0,.35)] [animation-delay:.1s] sm:text-6xl lg:text-7xl">
   Selamatkan Makanan,<br>Tutup Siklusnya,<br><span class="text-lime-300">Nol ke TPA.</span>
  </h1>
  <p class="mt-6 max-w-2xl animate-rise text-lg font-bold text-white/95 drop-shadow [animation-delay:.2s] sm:text-xl">Jaringan penyelamatan pangan yang menghubungkan restoran, hotel, dan katering dengan relawan serta panti di seluruh Indonesia.</p>

  <div class="mt-9 flex animate-rise flex-wrap gap-3 [animation-delay:.3s]">
   @php $arrow = '<svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8h10M9 4l4 4-4 4"/></svg>'; @endphp
   @php $primary = 'group inline-flex items-center gap-2 rounded-lg bg-lime-300 px-6 py-3.5 text-sm font-extrabold uppercase tracking-wide text-[#0c1f19] shadow-lg shadow-lime-300/20 transition hover:-translate-y-0.5 hover:bg-lime-200'; @endphp
   @if($role === 'provider')<a class="{{ $primary }}" href="{{ route('report.create') }}">Laporkan Surplus {!! $arrow !!}</a>
   @elseif($role)<a class="{{ $primary }}" href="{{ route('map') }}">{{ $role === 'processor' ? 'Cari Sisa Organik' : 'Cari Makanan' }} {!! $arrow !!}</a>
   @else<a class="{{ $primary }}" href="{{ route('register', ['role' => 'provider']) }}">Saya Punya Surplus {!! $arrow !!}</a>@endif
   <a class="inline-flex items-center gap-2 rounded-lg px-6 py-3.5 text-sm font-extrabold uppercase tracking-wide text-white ring-2 ring-white/70 transition hover:bg-white hover:text-[#0c1f19]" href="{{ route('map') }}">Lihat Peta</a>
  </div>
 </div>

 <a href="#fitur" class="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-1 text-[0.6875rem] font-bold uppercase tracking-[.2em] text-white/70 transition hover:text-white md:flex" aria-label="Gulir ke bawah">Gulir<span class="h-8 w-[2px] overflow-hidden rounded bg-white/20"><span class="block h-3 w-full animate-[scrollcue_1.6s_ease-in-out_infinite] bg-lime-300"></span></span></a>
</section>

{{-- TIGA PILAR: foto penuh kedua --}}
<section id="fitur" class="relative isolate overflow-hidden bg-[#143a2c] py-24 md:py-32">
 <x-bg-media name="fitur" overlay="bg-gradient-to-b from-[#0c1f19]/80 via-[#0c1f19]/70 to-[#0c1f19]/85" fallback="bg-gradient-to-b from-[#1f4b4a] to-[#143a2c]" />
 <div class="mx-auto grid max-w-6xl gap-12 px-4 md:grid-cols-3">
  @foreach([
   ['Lapor Kapan Saja', 'Surplus dari acara, prasmanan, atau toko roti? Laporkan dalam kurang dari satu menit, 24 jam sehari.'],
   ['Klaim Sendiri. Jemput Sendiri. Salurkan Langsung.', 'Penerima memilih makanan terdekat di peta, menghubungi pemberi via WhatsApp, lalu mengunggah bukti serah terima.'],
   ['Aman &amp; Terverifikasi. Keamanan Pangan Prioritas Kami.', 'Checklist kelayakan memisahkan yang layak konsumsi dari yang diolah menjadi kompos &amp; pakan maggot.'],
  ] as [$title, $desc])
   <div data-reveal style="transition-delay:{{ $loop->index * 120 }}ms"><span class="block h-1 w-12 rounded bg-lime-300"></span>
    <h2 class="mt-5 text-3xl font-extrabold leading-tight text-white drop-shadow md:text-[2rem]">{!! $title !!}</h2>
    <p class="mt-4 text-sm leading-relaxed text-white/75">{!! $desc !!}</p></div>
  @endforeach
 </div>
</section>

{{-- URGENSI: data masalah --}}
<section class="bg-lime-300 text-[#0c1f19]" aria-labelledby="urg-title">
 <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-12 lg:grid-cols-[1.1fr_2fr]">
  <div data-reveal><p class="text-[0.75rem] font-extrabold uppercase tracking-[.2em]">Mengapa mendesak</p><h2 id="urg-title" class="mt-2 text-3xl font-extrabold leading-tight">Indonesia membuang hingga 48 juta ton makanan per tahun.</h2>
   <p class="mt-2 text-[0.75rem] font-semibold text-[#0c1f19]/70">Sumber: Bappenas, Kajian Food Loss and Waste di Indonesia (2021).</p>
   <a href="{{ route('about') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-extrabold underline underline-offset-4">Lihat data &amp; kontribusi SDG →</a></div>
  <dl class="grid gap-6 sm:grid-cols-3">
   @foreach([['115–184 kg', 'makanan terbuang per orang per tahun'], ['Rp213–551 T', 'kerugian ekonomi setiap tahun'], ['61–125 jt', 'orang yang sebenarnya bisa diberi makan']] as [$v, $l])
    <div class="border-l-[3px] border-[#0c1f19] pl-4" data-reveal style="transition-delay:{{ $loop->index * 100 }}ms"><dd class="text-3xl font-extrabold">{{ $v }}</dd><dt class="mt-1 text-xs font-semibold">{{ $l }}</dt></div>
   @endforeach
  </dl>
 </div>
</section>

{{-- STATISTIK --}}
<section class="mx-auto mt-8 max-w-6xl px-4" data-reveal>
 <div class="grid gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 sm:grid-cols-4">
  @foreach([
   [$t['kg'], 0, 'kg', 'Makanan diselamatkan', '<path d="M4 11h16a8 8 0 0 1-16 0z"/><path d="M12 11V7m0 0c0-2 1.5-3.5 4-3.5 0 2.2-1.6 3.5-4 3.5z"/>'],
   [$t['meals'], 0, 'porsi', 'Porsi tersalurkan', '<path d="M3 13h18M5 13a7 7 0 0 1 14 0"/><path d="M12 6V4M2 17h20"/>'],
   [$t['co2_ton'], 2, 'ton', 'CO₂e dicegah', '<path d="M7 18a4.5 4.5 0 1 1 .6-8.96A6 6 0 0 1 19 11a3.5 3.5 0 0 1-.5 7z"/><path d="M9 14l2 2 4-4"/>'],
   [$t['partners'], 0, 'mitra', 'Mitra penerima & pengolah', '<path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10z"/>'],
  ] as [$v, $d, $s, $l, $i])
   <div class="flex items-center gap-3 sm:block"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-moss/10 text-moss sm:mb-2"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $i !!}</svg></span><div><b class="text-2xl text-slate-900"><span data-count="{{ $v }}" data-decimals="{{ $d }}">{{ number_format($v, $d, ',', '.') }}</span> <span class="text-sm text-moss">{{ $s }}</span></b><div class="text-[0.75rem] text-slate-500">{{ $l }}</div></div></div>
  @endforeach
 </div>
</section>

{{-- DARURAT --}}
@if($urgent->isNotEmpty())
<section class="mx-auto mt-14 max-w-6xl px-4">
 <div class="flex flex-wrap items-end justify-between gap-2" data-reveal><div><span class="chip bg-red-100 text-red-700">⏱ Butuh segera</span><h2 class="h2 mt-1">Selamatkan Sebelum Terlambat</h2><p class="text-xs text-slate-600">Makanan layak konsumsi dengan batas waktu terdekat. Klaim sekarang agar tidak terbuang.</p></div><a href="{{ route('map') }}" class="text-xs font-bold text-moss hover:underline">Lihat semua di peta →</a></div>
 <div class="mt-5 grid gap-4 md:grid-cols-3">
  @foreach($urgent as $r)
   <a href="{{ route('map', ['focus' => $r->id]) }}" class="card card-h group block" data-reveal style="transition-delay:{{ $loop->index * 90 }}ms">
    <div class="flex items-start justify-between gap-2"><span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-50 text-amber-700 transition group-hover:scale-110"><x-icon name="porsi" class="h-6 w-6" /></span>
     <span class="rounded-lg px-2 py-1 font-mono text-xs font-extrabold {{ $r->urgent_now ? 'bg-red-50 text-red-700' : 'bg-green-50 text-moss' }}" data-exp="{{ $r->expires_at->toIso8601String() }}">--:--:--</span></div>
    <h3 class="mt-3 text-sm font-extrabold">{{ $r->title }}</h3>
    <p class="text-xs text-slate-500">{{ $r->provider_name }} · {{ $r->portions }} porsi · {{ $r->weight_kg }} kg</p>
    <p class="mt-3 text-xs font-bold text-moss">Klaim di peta <span class="inline-block transition group-hover:translate-x-1">→</span></p>
   </a>
  @endforeach
 </div>
</section>
@endif

{{-- DUA JALUR --}}
<section id="jalur" class="mx-auto mt-16 max-w-6xl scroll-mt-20 px-4" x-data="{p:'human'}">
 <div class="text-center" data-reveal><h2 class="h2">Dua Jalur, <span class="text-moss">Nol Sampah</span></h2><p class="mx-auto mt-1 max-w-lg text-xs text-slate-600">Checklist kelayakan menentukan ke mana makanan Anda pergi. Pilih jalur untuk melihat perjalanannya.</p>
  <div class="mx-auto mt-4 inline-flex rounded-full bg-white p-1 shadow-sm ring-1 ring-black/5" role="tablist">
   <button role="tab" :aria-selected="p=='human'" @click="p='human'" class="rounded-full px-4 py-2 text-xs font-bold transition" :class="p=='human'?'bg-moss text-white shadow':'text-slate-500'">Human-First</button>
   <button role="tab" :aria-selected="p=='earth'" @click="p='earth'" class="rounded-full px-4 py-2 text-xs font-bold transition" :class="p=='earth'?'bg-amber-600 text-white shadow':'text-slate-500'">Earth-First</button>
  </div></div>
 @foreach(['human' => ['bg-green-50', 'text-moss', [['lapor', 'Lapor', 'Lolos checklist keamanan pangan'], ['klaim', 'Diklaim', 'Relawan/panti terdekat mengklaim'], ['jemput', 'Dijemput', 'Rute tercepat sebelum kedaluwarsa'], ['salur', 'Tersalurkan', 'Sampai ke penerima manfaat']]], 'earth' => ['bg-amber-50', 'text-amber-700', [['lapor', 'Lapor', 'Tidak lolos checklist / tak diklaim'], ['reroute', 'Auto re-route', 'Dialihkan otomatis ke jalur organik'], ['maggot', 'Maggot BSF', 'Diolah menjadi pakan ternak'], ['kompos', 'Kompos', 'Kembali menyuburkan tanah']]]] as $key => [$bg, $tx, $steps])
  <ol x-show="p=='{{ $key }}'" @if($key == 'earth') x-cloak @endif x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-3" class="mt-6 grid gap-4 sm:grid-cols-4">
   @foreach($steps as $i => [$ic, $a, $b])
    <li class="relative rounded-2xl {{ $bg }} p-5 text-center">
     @if($i < 3)<span class="absolute -right-3 top-1/2 z-10 hidden -translate-y-1/2 text-xl {{ $tx }} sm:block" aria-hidden="true">›</span>@endif
     <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-white shadow-sm {{ $tx }}"><x-icon :name="$ic" class="h-8 w-8" /></div><div class="mt-3 text-[0.6875rem] font-bold {{ $tx }}">LANGKAH {{ $i + 1 }}</div><b class="text-sm">{{ $a }}</b><p class="mt-1 text-[0.75rem] text-slate-500">{{ $b }}</p></li>
   @endforeach
  </ol>
 @endforeach
</section>

{{-- PETA + AKTIVITAS --}}
<section class="mx-auto mt-16 grid max-w-6xl gap-8 px-4 md:grid-cols-2">
 <div data-reveal><h2 class="h2">Peta Penyelamatan Interaktif</h2><p class="mt-1 text-xs text-slate-600">Temukan makanan surplus di sekitar Anda dan ambil peran dalam menyelamatkannya.</p>
  <ul class="mt-4 space-y-3 text-xs">@foreach([['peta', 'Peta real-time', 'Pin diperbarui otomatis setiap 30 detik'], ['filter', 'Filter radius & darurat', 'Fokus pada makanan terdekat yang paling mendesak'], ['whatsapp', 'Kontak WhatsApp langsung', 'Koordinasi penjemputan setelah klaim'], ['bukti', 'Bukti penyaluran', 'Foto serah terima + ucapan terima kasih']] as [$i, $a, $b])<li class="flex gap-3 rounded-xl p-2 transition hover:bg-white hover:shadow-sm"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-white text-moss shadow-sm"><x-icon :name="$i" class="h-5 w-5" /></span><span><b>{{ $a }}</b><br><span class="text-slate-500">{{ $b }}</span></span></li>@endforeach</ul>
  <a class="btn mt-4" href="{{ route('map') }}">Buka Peta Sekarang ›</a></div>
 <div class="card" data-reveal><div class="flex items-center justify-between"><h3 class="text-sm font-extrabold">Aktivitas Terbaru</h3><span class="chip bg-green-100 text-green-800"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-600"></span>Langsung</span></div>
  <ul class="mt-3 divide-y divide-slate-100">
   @forelse($recent as $r)
    @php [$ic, $tone] = ['waiting' => ['baru', 'bg-sky-50 text-sky-700'], 'claimed' => ['diklaim', 'bg-amber-50 text-amber-700'], 'on_the_way' => ['jemput', 'bg-indigo-50 text-indigo-700'], 'delivered' => ['tersalurkan', 'bg-green-50 text-moss']][$r->status] ?? ['baru', 'bg-slate-50 text-slate-500']; @endphp
    <li class="flex items-center gap-3 py-2.5 text-xs"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-full {{ $tone }}"><x-icon :name="$ic" class="h-4 w-4" /></span><span class="min-w-0 flex-1"><b class="block truncate">{{ $r->title }}</b><span class="text-slate-500">{{ $r->provider_name }} · {{ $r->status_label }}</span></span><span class="shrink-0 text-[0.6875rem] text-slate-400">{{ $r->updated_at->locale('id')->diffForHumans() }}</span></li>
   @empty<li class="py-3 text-xs text-slate-500">Belum ada aktivitas.</li>@endforelse
  </ul></div>
</section>

{{-- SIMULATOR DAMPAK --}}
<section class="mt-16 bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] px-4 py-14 text-white" x-data="{kg:25}">
 <div class="mx-auto grid max-w-6xl items-center gap-8 md:grid-cols-2">
  <div data-reveal><span class="chip bg-white/15"><x-icon name="hitung" class="h-3.5 w-3.5" /> Simulator Dampak</span><h2 class="mt-2 text-2xl font-extrabold">Berapa dampak dari surplus Anda?</h2><p class="mt-2 text-xs text-white/80">Geser untuk memperkirakan berapa porsi yang bisa disalurkan dan emisi yang dicegah dari makanan yang biasanya terbuang setiap minggu.</p>
   <label for="sim" class="mt-6 block text-xs font-bold">Surplus per minggu: <span class="text-lg text-lime-300" x-text="kg+' kg'"></span></label>
   <input id="sim" type="range" min="1" max="200" x-model.number="kg" class="mt-2 w-full accent-lime-400">
   <div class="flex justify-between text-[0.6875rem] text-white/60"><span>1 kg</span><span>Restoran kecil ~10 kg</span><span>Hotel ~100 kg</span><span>200 kg</span></div></div>
  <div class="grid grid-cols-2 gap-3" data-reveal>
   @foreach([["Math.round(kg/$K).toLocaleString('id-ID')", 'porsi / minggu', 'porsi'], ["(kg*$C).toLocaleString('id-ID',{maximumFractionDigits:1})", 'kg CO₂e dicegah', 'co2'], ["Math.round(kg*$C/$CAR).toLocaleString('id-ID')", 'km perjalanan mobil setara', 'mobil'], ["(kg*$C*52/$TREE).toLocaleString('id-ID',{maximumFractionDigits:0})", 'pohon setara / tahun', 'pohon']] as [$expr, $l, $i])
    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/15"><span class="grid h-10 w-10 place-items-center rounded-xl bg-lime-300 text-[#0c1f19]"><x-icon :name="$i" class="h-5 w-5" /></span><b class="mt-3 block text-2xl" x-text="{{ $expr }}"></b><div class="text-[0.75rem] text-white/70">{{ $l }}</div></div>
   @endforeach
  </div>
 </div>
</section>

@endsection
