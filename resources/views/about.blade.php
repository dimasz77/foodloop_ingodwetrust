@extends('layout')
@section('title','Tentang')
@section('meta_description', 'FoodLoop: platform penyelamatan surplus pangan untuk SDG 2 (Zero Hunger) dan SDG 12 (Responsible Consumption & Production).')
@section('content')
@php
  $nf = fn ($v, $d = 0) => number_format($v, $d, ',', '.');
  $yes = '<svg class="mx-auto h-5 w-5 text-moss" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-label="Ya" role="img"><path d="M4 10.5l4 4 8-9"/></svg>';
  $dash = '<span class="text-slate-300" aria-label="Tidak">—</span>';
  $part = '<span class="text-[0.6875rem] font-bold text-amber-700">Sebagian</span>';
@endphp

{{-- PITA 1: masalah --}}
<x-page-hero image="tentang.jpg" eyebrow="Tentang FoodLoop" title="Indonesia Membuang Makanan, Sementara Jutaan Orang Kekurangan" subtitle="FoodLoop menutup siklus itu: makanan yang masih layak sampai ke manusia, sisanya diolah menjadi pakan maggot dan kompos. Tidak ada yang berakhir di TPA." :crumbs="['Tentang' => null]">
 <span class="inline-flex items-center gap-2 rounded-lg bg-[#DDA63A] py-1 pl-1 pr-3 text-xs font-extrabold text-[#0c1f19]"><span class="grid h-7 w-7 place-items-center rounded bg-[#0c1f19]/15 text-sm">2</span>SDG 2 · Zero Hunger</span>
 <span class="inline-flex items-center gap-2 rounded-lg bg-[#BF8B2E] py-1 pl-1 pr-3 text-xs font-extrabold text-[#0c1f19]"><span class="grid h-7 w-7 place-items-center rounded bg-[#0c1f19]/15 text-sm">12</span>SDG 12 · Responsible Consumption</span>
 <x-slot:after>
  <dl class="grid divide-y divide-white/10 overflow-hidden rounded-2xl bg-white/[.06] ring-1 ring-white/10 backdrop-blur sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x">
   @foreach([['23–48 jt ton', 'makanan terbuang per tahun di Indonesia (2000–2019)'], ['115–184 kg', 'per orang per tahun'], ['Rp213–551 T', 'kerugian ekonomi per tahun, ±4–5% PDB'], ['61–125 jt', 'orang yang sebenarnya bisa diberi makan']] as [$v, $l])
    <div class="p-6"><dd class="text-3xl font-extrabold text-lime-300">{{ $v }}</dd><dt class="mt-1 text-xs text-white/75">{{ $l }}</dt></div>
   @endforeach
  </dl>
  <p class="mt-3 text-[0.6875rem] text-white/60">Sumber: Bappenas, <i>Laporan Kajian Food Loss and Waste di Indonesia</i> (2021).</p>
 </x-slot:after>
</x-page-hero>

{{-- PITA 2: konsekuensi --}}
<section class="bg-lime-300 text-[#0c1f19]">
 <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 md:grid-cols-3">
  @foreach([
   ['Jika dibiarkan', 'Sampah makanan yang membusuk di TPA menghasilkan gas metana, salah satu gas rumah kaca paling kuat, dan memicu risiko longsor serta kebakaran TPA.'],
   ['Paradoks pangan', 'Di saat yang sama, masih banyak keluarga, panti, dan dapur umum yang kekurangan makanan bergizi. Masalahnya bukan kurang makanan, tetapi distribusi.'],
   ['Akar masalah', 'Pemberi tidak tahu siapa yang membutuhkan saat itu juga, dan makanan punya batas waktu. Koordinasi manual lewat grup chat terlalu lambat.'],
  ] as [$h, $p])
   <div class="border-l-[3px] border-[#0c1f19] pl-4" data-reveal style="transition-delay:{{ $loop->index * 100 }}ms"><h2 class="text-xl font-extrabold">{{ $h }}</h2><p class="mt-2 text-sm font-medium leading-relaxed text-[#0c1f19]/85">{{ $p }}</p></div>
  @endforeach
 </div>
</section>

{{-- PITA 3: pemetaan SDG --}}
<section class="border-b border-slate-200 bg-white">
 <div class="mx-auto max-w-6xl px-4 py-14">
  <p class="sec" data-reveal><span class="h-[3px] w-8 rounded bg-moss"></span>Kontribusi pada SDGs</p>
  <h2 class="mt-2 text-3xl font-extrabold" data-reveal>Setiap Fitur Terhubung ke Target SDG</h2>
  <p class="mt-2 max-w-2xl text-sm text-slate-600" data-reveal>Angka di kolom terakhir dihitung langsung dari data aplikasi, bukan angka statis.</p>
  <div class="mt-8 overflow-x-auto rounded-2xl ring-1 ring-slate-200" data-reveal>
   <table class="w-full min-w-[640px] text-left text-sm">
    <caption class="sr-only">Pemetaan target SDG ke fitur FoodLoop dan capaiannya</caption>
    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th scope="col" class="p-4">Target SDG</th><th scope="col" class="p-4">Fitur FoodLoop</th><th scope="col" class="p-4 text-right">Capaian saat ini</th></tr></thead>
    <tbody class="divide-y divide-slate-100">
     @foreach([
      ['#DDA63A', '2.1', 'Menjamin akses pangan aman & bergizi bagi semua orang', 'Jalur Human-First: checklist keamanan pangan, klaim oleh penerima terdekat, bukti serah terima', $nf($t['meals']).' porsi tersalurkan'],
      ['#BF8B2E', '12.3', 'Mengurangi separuh food waste per kapita', 'Lapor surplus < 1 menit, peta real-time, notifikasi darurat sebelum kedaluwarsa', $nf($t['kg']).' kg diselamatkan'],
      ['#BF8B2E', '12.5', 'Mengurangi timbulan sampah melalui daur ulang', 'Jalur Earth-First & auto re-route ke Mitra Pengolah Organik (maggot BSF/kompos)', $nf($organicKg).' kg diolah'],
      ['#3F7E44', '13', 'Aksi iklim (terkait)', 'Estimasi CO₂e yang dicegah untuk setiap laporan, ditampilkan di halaman Dampak', $nf($t['co2_ton'], 2).' ton CO₂e'],
     ] as [$c, $code, $goal, $feat, $val])
      <tr>
       <td class="p-4 align-top"><span class="inline-flex items-center gap-2"><span class="grid h-9 w-11 shrink-0 place-items-center rounded font-extrabold text-white" style="background:{{ $c }}">{{ $code }}</span><span class="text-xs font-semibold text-slate-700">{{ $goal }}</span></span></td>
       <td class="p-4 align-top text-slate-600">{{ $feat }}</td>
       <td class="whitespace-nowrap p-4 text-right align-top font-extrabold text-moss">{{ $val }}</td>
      </tr>
     @endforeach
    </tbody>
   </table>
  </div>
 </div>
</section>

{{-- PITA 4: pembeda --}}
<section class="relative isolate overflow-hidden bg-[#0c1f19] text-white">
 <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#0c1f19] to-[#1f4b4a]"><x-contour /></div>
 <div class="mx-auto max-w-6xl px-4 py-16">
  <p class="sec !text-lime-300" data-reveal><span class="h-[3px] w-8 rounded bg-lime-300"></span>Apa yang berbeda</p>
  <h2 class="mt-2 text-3xl font-extrabold" data-reveal>Bukan Sekadar Donasi Makanan</h2>
  <p class="mt-2 max-w-2xl text-sm text-white/70" data-reveal>Perbandingan dengan pendekatan yang umum dipakai saat ini (gambaran umum, bukan merujuk produk tertentu).</p>
  <div class="mt-8 overflow-x-auto rounded-2xl bg-white text-slate-800" data-reveal>
   <table class="w-full min-w-[640px] text-sm">
    <caption class="sr-only">Perbandingan FoodLoop dengan pendekatan lain</caption>
    <thead class="text-xs uppercase tracking-wider text-slate-500"><tr class="border-b"><th scope="col" class="p-4 text-left">Kemampuan</th><th scope="col" class="bg-lime-100 p-4 text-[#0c1f19]">FoodLoop</th><th scope="col" class="p-4">Koordinasi manual (grup chat)</th><th scope="col" class="p-4">Aplikasi jual-diskon surplus</th></tr></thead>
    <tbody class="divide-y divide-slate-100 text-center">
     @foreach([
      ['Gratis bagi penerima', $yes, $yes, $dash],
      ['Checklist kelayakan & keamanan pangan', $yes, $dash, $part],
      ['Jalur untuk makanan tidak layak (maggot/kompos)', $yes, $dash, $dash],
      ['Auto re-route bila tidak diklaim sebelum kedaluwarsa', $yes, $dash, $dash],
      ['Peta real-time, filter radius & darurat', $yes, $dash, $part],
      ['Klaim anti-bentrok & bukti serah terima', $yes, $dash, $part],
      ['Dampak CO₂e terukur & gamifikasi lencana', $yes, $dash, $part],
     ] as [$k, $a, $b, $c])
      <tr><th scope="row" class="p-4 text-left font-semibold">{{ $k }}</th><td class="bg-lime-50 p-4">{!! $a !!}</td><td class="p-4">{!! $b !!}</td><td class="p-4">{!! $c !!}</td></tr>
     @endforeach
    </tbody>
   </table>
  </div>
 </div>
</section>

{{-- PITA 5: panduan uji coba --}}
<section id="uji" class="scroll-mt-20 bg-cream">
 <div class="mx-auto max-w-6xl px-4 py-16">
  <p class="sec" data-reveal><span class="h-[3px] w-8 rounded bg-moss"></span>Panduan uji coba</p>
  <h2 class="mt-2 text-3xl font-extrabold" data-reveal>Coba Seluruh Siklus dalam 5 Menit</h2>
  <p class="mt-2 text-sm text-slate-600" data-reveal>Semua akun demo memakai kata sandi <code class="rounded bg-white px-1.5 py-0.5 font-bold ring-1 ring-slate-200">password</code>. Tekan tombol untuk langsung mengisi form masuk.</p>
  <ol class="mt-8 grid gap-4 md:grid-cols-3">
   @foreach([
    ['1', 'Pemberi Makanan', 'smiley@foodloop.test', ['Buka Lapor, isi detail & checklist', 'Lihat prediksi jalur & mitra terdekat', 'Pantau status, kontak penerima, pesan terima kasih']],
    ['2', 'Penerima Makanan', 'relawan@foodloop.test', ['Buka Peta, aktifkan notifikasi darurat', 'Klaim makanan layak, hubungi via WhatsApp', 'Unggah foto bukti & ucapan terima kasih']],
    ['3', 'Mitra Pengolah Organik', 'bsf@foodloop.test', ['Filter peta otomatis ke Organik', 'Klaim sisa organik untuk maggot/kompos', 'Lihat lencana Sahabat Bumi di Riwayat']],
   ] as [$n, $role, $email, $steps])
    <li class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5" data-reveal style="transition-delay:{{ $loop->index * 100 }}ms">
     <span class="grid h-10 w-10 place-items-center rounded-full bg-[#0c1f19] font-extrabold text-lime-300">{{ $n }}</span>
     <h3 class="mt-4 text-lg font-extrabold">{{ $role }}</h3><p class="text-xs text-slate-500">{{ $email }}</p>
     <ul class="mt-4 flex-1 space-y-2 text-sm text-slate-600">@foreach($steps as $s)<li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-moss"></span>{{ $s }}</li>@endforeach</ul>
     @guest<a class="btn btn-g mt-5" href="{{ route('login', ['demo' => $email]) }}">Masuk sebagai {{ $role }}</a>@endguest
    </li>
   @endforeach
  </ol>
 </div>
</section>

{{-- PITA 6: teknologi --}}
<section class="border-t border-slate-200 bg-white">
 <div class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1fr_2fr]">
  <div data-reveal><p class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Di balik layar</p><h2 class="mt-2 text-3xl font-extrabold">Smart, Scalable &amp; Intuitive</h2><p class="mt-3 text-sm text-slate-600">Dibangun sebagai MVP yang siap tumbuh dari satu kota ke seluruh Indonesia.</p></div>
  <dl class="grid gap-6 sm:grid-cols-2">
   @foreach([
    ['Smart', 'Checklist kelayakan menentukan jalur otomatis; mitra terdekat dihitung dengan rumus Haversine; auto re-route saat batas waktu habis.'],
    ['Scalable', 'Laravel 13 dengan database yang bisa diganti dari SQLite ke MySQL lewat .env; endpoint JSON untuk pembaruan peta; logika bisnis terpusat di ImpactCalculator.'],
    ['Intuitive', 'Diuji dengan 10 heuristik Nielsen: status selalu terlihat, pencegahan kesalahan, pintasan keyboard, dan bantuan kontekstual di setiap halaman.'],
    ['Aksesibel', 'Navigasi penuh dengan keyboard, label ARIA, cincin fokus, panel ukuran teks & kontras tinggi, serta menghormati pengaturan reduced motion.'],
    ['Bisa di-install', 'Progressive Web App: dapat dipasang di layar utama ponsel Android dan memiliki halaman offline.'],
    ['Teruji', 'Feature test otomatis untuk alur klaim, anti-bentrok, peran, dan penyelesaian; dijalankan otomatis lewat GitHub Actions.'],
   ] as [$h, $p])
    <div class="border-l-[3px] border-moss pl-4" data-reveal style="transition-delay:{{ $loop->index * 60 }}ms"><dt class="font-extrabold">{{ $h }}</dt><dd class="mt-1 text-sm text-slate-600">{{ $p }}</dd></div>
   @endforeach
  </dl>
 </div>
</section>
@endsection
