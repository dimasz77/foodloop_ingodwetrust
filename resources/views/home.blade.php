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
<section class="relative isolate flex min-h-[calc(100vh-4.5rem)] items-center overflow-hidden bg-[#0c1f19] md:min-h-[78vh]">
 <x-bg-media name="hero" kenburns />
 <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#0c1f19]/90 via-[#0c1f19]/55 to-[#0c1f19]/10"></div>
 <div class="absolute inset-x-0 bottom-0 -z-10 h-40 bg-gradient-to-t from-[#0c1f19]/70 to-transparent"></div>

 <div class="mx-auto w-full max-w-6xl px-4 py-16">
  <a href="{{ route('map') }}" class="group inline-flex animate-rise items-center gap-2 rounded-full bg-white/10 py-1 pl-1 pr-3 text-xs font-semibold text-white ring-1 ring-white/20 backdrop-blur transition hover:bg-white/15">
   <span class="inline-flex items-center gap-1.5 rounded-full bg-red-600 px-2 py-0.5 font-bold"><span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ring rounded-full bg-white"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-white"></span></span>LIVE</span>
   {{ $waiting }} makanan menunggu diselamatkan sekarang
   <svg class="h-3 w-3 transition group-hover:translate-x-0.5" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 2l4 4-4 4"/></svg></a>

  <h1 class="mt-6 max-w-3xl animate-rise text-[2.7rem] font-extrabold leading-[1.04] tracking-[-0.02em] text-white drop-shadow-[0_2px_12px_rgba(0,0,0,.35)] [animation-delay:.1s] sm:text-6xl lg:text-7xl">
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
  <a href="{{ route('about') }}" class="mt-8 inline-flex animate-rise flex-wrap items-center gap-2 text-xs font-semibold text-white/80 transition hover:text-white [animation-delay:.4s]" aria-label="Lihat kontribusi FoodLoop pada SDG 2 dan SDG 12">
   <span>Mendukung</span>
   <span class="inline-flex items-center gap-1.5 rounded-md bg-[#DDA63A] py-0.5 pl-0.5 pr-2 font-extrabold text-[#0c1f19]"><span class="grid h-5 w-5 place-items-center rounded bg-[#0c1f19]/15 text-[0.6875rem]">2</span>Zero Hunger</span>
   <span class="inline-flex items-center gap-1.5 rounded-md bg-[#BF8B2E] py-0.5 pl-0.5 pr-2 font-extrabold text-[#0c1f19]"><span class="grid h-5 w-5 place-items-center rounded bg-[#0c1f19]/15 text-[0.6875rem]">12</span>Responsible Consumption</span>
   <span class="underline underline-offset-2">Pelajari →</span>
  </a>
 </div>

 <a href="#fitur" class="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-1 text-[0.6875rem] font-bold uppercase tracking-[.2em] text-white/70 transition hover:text-white md:flex" aria-label="Gulir ke bawah">Gulir<span class="h-8 w-[2px] overflow-hidden rounded bg-white/20"><span class="block h-3 w-full animate-[scrollcue_1.6s_ease-in-out_infinite] bg-lime-300"></span></span></a>
</section>

{{-- TIGA PILAR: foto penuh kedua --}}
<section id="fitur" class="relative isolate overflow-hidden bg-[#143a2c] py-24 md:py-32">
 <x-bg-media name="fitur" overlay="bg-[#0c1f19]/65" fallback="bg-gradient-to-b from-[#1f4b4a] to-[#143a2c]" />
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
    <div class="flex items-start justify-between gap-2"><span class="grid h-11 w-11 place-items-center rounded-xl bg-amber-50 text-2xl transition group-hover:scale-110">🍱</span>
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
<section class="mx-auto mt-16 max-w-6xl px-4" x-data="{p:'human'}">
 <div class="text-center" data-reveal><h2 class="h2">Dua Jalur, <span class="text-moss">Nol Sampah</span></h2><p class="mx-auto mt-1 max-w-lg text-xs text-slate-600">Checklist kelayakan menentukan ke mana makanan Anda pergi. Pilih jalur untuk melihat perjalanannya.</p>
  <div class="mx-auto mt-4 inline-flex rounded-full bg-white p-1 shadow-sm ring-1 ring-black/5" role="tablist">
   <button role="tab" :aria-selected="p=='human'" @click="p='human'" class="rounded-full px-4 py-2 text-xs font-bold transition" :class="p=='human'?'bg-moss text-white shadow':'text-slate-500'">🍱 Human-First</button>
   <button role="tab" :aria-selected="p=='earth'" @click="p='earth'" class="rounded-full px-4 py-2 text-xs font-bold transition" :class="p=='earth'?'bg-amber-500 text-white shadow':'text-slate-500'">♻️ Earth-First</button>
  </div></div>
 @foreach(['human' => ['bg-green-50', 'text-moss', [['📝', 'Lapor', 'Lolos checklist keamanan pangan'], ['📍', 'Diklaim', 'Relawan/panti terdekat mengklaim'], ['🚚', 'Dijemput', 'Rute tercepat sebelum kedaluwarsa'], ['🤲', 'Tersalurkan', 'Sampai ke penerima manfaat']]], 'earth' => ['bg-amber-50', 'text-amber-700', [['📝', 'Lapor', 'Tidak lolos checklist / tak diklaim'], ['🔁', 'Auto re-route', 'Dialihkan otomatis ke jalur organik'], ['🪱', 'Maggot BSF', 'Diolah menjadi pakan ternak'], ['🌱', 'Kompos', 'Kembali menyuburkan tanah']]]] as $key => [$bg, $tx, $steps])
  <ol x-show="p=='{{ $key }}'" @if($key == 'earth') x-cloak @endif x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-3" class="mt-6 grid gap-4 sm:grid-cols-4">
   @foreach($steps as $i => [$ic, $a, $b])
    <li class="relative rounded-2xl {{ $bg }} p-5 text-center">
     @if($i < 3)<span class="absolute -right-3 top-1/2 z-10 hidden -translate-y-1/2 text-xl {{ $tx }} sm:block" aria-hidden="true">›</span>@endif
     <div class="text-3xl">{{ $ic }}</div><div class="mt-1 text-[0.6875rem] font-bold {{ $tx }}">LANGKAH {{ $i + 1 }}</div><b class="text-sm">{{ $a }}</b><p class="mt-1 text-[0.75rem] text-slate-500">{{ $b }}</p></li>
   @endforeach
  </ol>
 @endforeach
</section>

{{-- PETA + AKTIVITAS --}}
<section class="mx-auto mt-16 grid max-w-6xl gap-8 px-4 md:grid-cols-2">
 <div data-reveal><h2 class="h2">Peta Penyelamatan Interaktif</h2><p class="mt-1 text-xs text-slate-600">Temukan makanan surplus di sekitar Anda dan ambil peran dalam menyelamatkannya.</p>
  <ul class="mt-4 space-y-3 text-xs">@foreach([['📍', 'Peta real-time', 'Pin diperbarui otomatis setiap 30 detik'], ['📏', 'Filter radius & darurat', 'Fokus pada makanan terdekat yang paling mendesak'], ['💬', 'Kontak WhatsApp langsung', 'Koordinasi penjemputan setelah klaim'], ['📷', 'Bukti penyaluran', 'Foto serah terima + ucapan terima kasih']] as [$i, $a, $b])<li class="flex gap-3 rounded-xl p-2 transition hover:bg-white hover:shadow-sm"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white text-lg shadow-sm">{{ $i }}</span><span><b>{{ $a }}</b><br><span class="text-slate-500">{{ $b }}</span></span></li>@endforeach</ul>
  <a class="btn mt-4" href="{{ route('map') }}">Buka Peta Sekarang ›</a></div>
 <div class="card" data-reveal><div class="flex items-center justify-between"><h3 class="text-sm font-extrabold">Aktivitas Terbaru</h3><span class="chip bg-green-100 text-green-800"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-600"></span>Langsung</span></div>
  <ul class="mt-3 divide-y divide-slate-100">
   @forelse($recent as $r)
    @php $ic = ['waiting' => '🆕', 'claimed' => '🙋', 'on_the_way' => '🚚', 'delivered' => '✅'][$r->status] ?? '•'; @endphp
    <li class="flex items-center gap-3 py-2.5 text-xs"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-slate-50">{{ $ic }}</span><span class="min-w-0 flex-1"><b class="block truncate">{{ $r->title }}</b><span class="text-slate-500">{{ $r->provider_name }} · {{ $r->status_label }}</span></span><span class="shrink-0 text-[0.6875rem] text-slate-400">{{ $r->updated_at->locale('id')->diffForHumans() }}</span></li>
   @empty<li class="py-3 text-xs text-slate-500">Belum ada aktivitas.</li>@endforelse
  </ul></div>
</section>

{{-- SIMULATOR DAMPAK --}}
<section class="mt-16 bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] px-4 py-14 text-white" x-data="{kg:25}">
 <div class="mx-auto grid max-w-6xl items-center gap-8 md:grid-cols-2">
  <div data-reveal><span class="chip bg-white/15">🧮 Simulator Dampak</span><h2 class="mt-2 text-2xl font-extrabold">Berapa dampak dari surplus Anda?</h2><p class="mt-2 text-xs text-white/80">Geser untuk memperkirakan berapa porsi yang bisa disalurkan dan emisi yang dicegah dari makanan yang biasanya terbuang setiap minggu.</p>
   <label for="sim" class="mt-6 block text-xs font-bold">Surplus per minggu: <span class="text-lg text-lime-300" x-text="kg+' kg'"></span></label>
   <input id="sim" type="range" min="1" max="200" x-model.number="kg" class="mt-2 w-full accent-lime-400">
   <div class="flex justify-between text-[0.6875rem] text-white/60"><span>1 kg</span><span>Restoran kecil ~10 kg</span><span>Hotel ~100 kg</span><span>200 kg</span></div></div>
  <div class="grid grid-cols-2 gap-3" data-reveal>
   @foreach([["Math.round(kg/$K).toLocaleString('id-ID')", 'porsi / minggu', '🍱'], ["(kg*$C).toLocaleString('id-ID',{maximumFractionDigits:1})", 'kg CO₂e dicegah', '🌍'], ["Math.round(kg*$C/$CAR).toLocaleString('id-ID')", 'km perjalanan mobil setara', '🚗'], ["(kg*$C*52/$TREE).toLocaleString('id-ID',{maximumFractionDigits:0})", 'pohon setara / tahun', '🌳']] as [$expr, $l, $i])
    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10 backdrop-blur transition hover:bg-white/15"><div class="text-2xl">{{ $i }}</div><b class="text-2xl" x-text="{{ $expr }}"></b><div class="text-[0.75rem] text-white/70">{{ $l }}</div></div>
   @endforeach
  </div>
 </div>
</section>

{{-- TESTIMONI --}}
<section class="mx-auto max-w-6xl px-4 pt-14" x-data="{i:0,q:[['Sejak bergabung, sisa prasmanan kami tidak pernah lagi berakhir di tempat sampah. Prosesnya cepat sekali.','Killian Murphy','Manajer F&B, Sun Hotel'],['Kami menerima makanan layak hampir setiap minggu untuk anak-anak panti. Notifikasi daruratnya sangat membantu.','Ellen Woods','Pengurus Panti Asuhan Kasih'],['Sisa sayur dari dapur kami sekarang jadi pakan maggot. Rasanya ikut menjaga bumi.','Rina Siregar','Pemilik Bakery Delight']]}" x-init="setInterval(()=>i=(i+1)%q.length,6000)">
 <h2 class="h2 text-center" data-reveal>Kata Mitra Tentang FoodLoop</h2>
 <figure class="card relative mx-auto mt-6 max-w-2xl text-center" data-reveal aria-live="polite"><span class="absolute left-5 top-2 font-serif text-6xl text-moss/20" aria-hidden="true">“</span>
  <template x-for="(t,k) in q" :key="k"><div x-show="i==k" x-transition.opacity.duration.500ms><blockquote class="text-sm text-slate-600" x-text="t[0]"></blockquote><figcaption class="mt-4 text-xs"><b x-text="t[1]"></b><br><span class="text-slate-500" x-text="t[2]"></span></figcaption></div></template>
  <div class="mt-4 flex justify-center gap-2"><template x-for="(t,k) in q" :key="k"><button @click="i=k" class="h-2 rounded-full transition-all" :class="i==k?'w-6 bg-moss':'w-2 bg-slate-300'" :aria-label="'Testimoni '+(k+1)"></button></template></div>
 </figure>
 <div class="mt-8 flex items-center justify-center gap-4" data-reveal><span class="text-xs font-bold text-slate-500">Mitra Potensial</span><span class="rounded-lg bg-blue-900 px-4 py-2 font-extrabold text-white">del</span></div>
</section>

{{-- FAQ --}}
<section id="faq" class="mx-auto max-w-3xl scroll-mt-24 px-4 pb-20 pt-14" x-data="{o:0}">
 <h2 class="h2 text-center" data-reveal>Pertanyaan yang Sering Diajukan</h2>
 <div class="mt-6 space-y-3">
  @foreach([
   ['Makanan seperti apa yang boleh dilaporkan?', 'Semua surplus makanan: nasi kotak, roti, prasmanan, hingga sisa sayur dan buah. Checklist kelayakan akan menentukan apakah makanan disalurkan ke manusia (Human-First) atau ke pengolah organik (Earth-First).'],
   ['Bagaimana jika tidak ada yang mengklaim sebelum kedaluwarsa?', 'Sistem melakukan auto re-route: laporan otomatis dialihkan ke mitra pengolah organik terdekat (maggot BSF atau kompos), sehingga tetap tidak berakhir di TPA.'],
   ['Siapa yang bisa menjadi penerima?', 'Relawan, panti asuhan, panti jompo, dapur umum, dan pengolah organik. Daftar sebagai Penerima Makanan lalu klaim makanan dari peta.'],
   ['Bagaimana cara menghubungi pemberi makanan?', 'Setelah Anda mengklaim, tombol WhatsApp pemberi akan muncul di halaman status dan peta, lengkap dengan pesan pembuka otomatis.'],
   ['Dari mana angka CO₂e berasal?', 'Angka adalah estimasi: setiap kg makanan yang diselamatkan diasumsikan mencegah '.str_replace('.', ',', $C).' kg CO₂e, dan 1 porsi ≈ '.str_replace('.', ',', $K).' kg.'],
  ] as $i => [$q, $a])
   <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-black/5 transition" :class="o=={{ $i }}&&'shadow-md ring-moss/30'" data-reveal>
    <button class="flex w-full items-center justify-between gap-3 p-4 text-left text-sm font-bold" @click="o=o=={{ $i }}?null:{{ $i }}" :aria-expanded="o=={{ $i }}">{{ $q }}<span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-slate-100 transition" :class="o=={{ $i }}&&'rotate-45 bg-moss text-white'">+</span></button>
    <div x-show="o=={{ $i }}" x-transition.opacity.duration.300ms x-cloak class="px-4 pb-4 text-xs text-slate-600">{{ $a }}</div>
   </div>
  @endforeach
 </div>
</section>
@endsection
