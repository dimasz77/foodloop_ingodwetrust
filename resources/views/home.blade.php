@extends('layout')
@section('title','Beranda')
@section('shell')
<div class="relative overflow-hidden bg-[radial-gradient(ellipse_at_top,#2f7a34,#14401f_70%)] text-white">
 <span class="pointer-events-none absolute -left-6 top-40 select-none text-[9rem] opacity-90 md:text-[12rem]" aria-hidden="true">🥬</span>
 <span class="pointer-events-none absolute -right-4 top-36 select-none text-[9rem] opacity-90 md:text-[12rem]" aria-hidden="true">🍆</span>
 <header class="relative mx-auto flex max-w-6xl items-center justify-between px-5 py-5">
  <a href="{{ route('home') }}" class="text-xl font-extrabold tracking-wide text-lime-300">🌿 FOODLOOP</a>
  <nav class="hidden gap-8 text-sm font-semibold text-lime-300 md:flex" aria-label="Navigasi utama"><a href="{{ route('map') }}">Peta</a><a href="#cara">Cara Kerja</a><a href="{{ route('impact') }}">Impact</a><a href="#kontak">Kontak</a></nav>
  <a class="rounded-full bg-lime-300 px-5 py-2 text-sm font-extrabold text-green-950" href="{{ route('report.create') }}">Mulai Lapor</a>
 </header>
 <section class="relative mx-auto max-w-4xl px-5 pb-10 pt-12 text-center">
  <h1 class="font-serif text-4xl font-semibold leading-[1.05] md:text-7xl">Tutup Siklus Sampah Pangan, <span class="text-lime-300">Berikan Impact Nyata</span></h1>
  <p class="mt-6 font-serif text-xl">Setiap porsi yang diselamatkan berarti.</p>
  <div class="mt-8 flex flex-wrap justify-center gap-3"><a class="rounded-full bg-lime-300 px-7 py-3 font-extrabold text-green-950" href="{{ route('report.create') }}">Lapor Makanan</a><a class="rounded-full bg-white px-7 py-3 font-extrabold text-slate-900" href="{{ route('map') }}">Lihat Peta</a></div>
 </section>
 <div class="relative mx-auto max-w-5xl px-5">
  <div class="grid gap-4 rounded-t-3xl bg-[#f6f4ec] p-4 text-slate-800 shadow-2xl md:grid-cols-[1fr_260px]">
   <div><div class="rounded-2xl bg-lime-100 p-4"><b>Dampak Kita</b><div class="mt-3 grid gap-3 sm:grid-cols-3">
    <div class="rounded-xl bg-white/80 p-3"><b class="text-2xl">{{ number_format($t['kg'],0,',','.') }} kg</b><div class="text-xs text-slate-500">Food Rescued</div></div>
    <div class="rounded-xl bg-white/80 p-3"><b class="text-2xl">{{ number_format($t['meals'],0,',','.') }}</b><div class="text-xs text-slate-500">Meal Distributed</div></div>
    <div class="rounded-xl bg-white/80 p-3"><b class="text-2xl">{{ number_format($t['co2_ton'],2,',','.') }} ton</b><div class="text-xs text-slate-500">CO₂e Prevented</div></div></div></div>
    <b class="mt-4 block">Laporan Terbaru</b><div class="mt-2 overflow-x-auto rounded-xl bg-white"><table class="w-full text-left text-xs"><thead class="text-slate-400"><tr><th class="p-2">Makanan</th><th class="p-2">Penyedia</th><th class="p-2">Jalur</th><th class="p-2">Jumlah</th></tr></thead><tbody>
    @foreach($recent as $r)<tr class="border-t"><td class="p-2">{{ $r->title }}</td><td class="p-2">{{ $r->provider_name }}</td><td class="p-2"><span class="rounded-full bg-green-100 px-2 py-0.5">{{ $r->category=='layak'?'Layak':'Organik' }}</span></td><td class="p-2">{{ $r->weight_kg }} kg</td></tr>@endforeach</tbody></table></div></div>
   <aside class="rounded-2xl bg-green-100 p-4"><b>Perlu Segera Diambil</b><p class="text-xs text-slate-500">Makanan layak yang hampir kedaluwarsa.</p>
    @foreach($urgent as $r)<div class="mt-3 rounded-xl bg-white p-3 text-sm"><b>{{ $r->title }}</b><div class="text-xs text-slate-500">{{ $r->provider_name }}</div><a href="{{ route('map') }}" class="mt-2 block rounded-lg bg-lime-400 py-1.5 text-center font-bold text-green-950">Klaim</a></div>@endforeach</aside>
  </div></div>
</div>
<div class="mx-auto max-w-6xl px-5 py-14">
 <section><h2 class="font-serif text-3xl font-semibold">Interactive Rescue Map</h2><div class="mt-5 grid gap-4 md:grid-cols-3">
  @foreach([['📍','Peta real-time','Hijau layak, oranye organik, merah mendesak.'],['🧭','Klaim & rute','Klaim lalu ikuti rute sebelum waktu habis.'],['📷','Bukti penyaluran','Unggah foto serah terima untuk validasi dampak.']] as [$i,$a,$b])<div class="card"><div class="text-3xl">{{ $i }}</div><b>{{ $a }}</b><p class="text-sm text-slate-600">{{ $b }}</p></div>@endforeach</div></section>
 <section id="cara" class="mt-14"><h2 class="font-serif text-3xl font-semibold">How FoodLoop Works?</h2><ol class="mt-5 grid gap-4 md:grid-cols-4">
  @foreach([['Laporkan','Penyedia melapor surplus kurang dari 1 menit.'],['Ditemukan','Relawan atau penerima melihat dan mengklaim.'],['Dijemput','Relawan menjemput lewat rute terdekat.'],['Disalurkan','Makanan diserahkan, dampak tercatat.']] as $i=>[$a,$b])<li class="card"><span class="font-serif text-4xl text-green-700">{{ $i+1 }}</span><b class="block">{{ $a }}</b><span class="text-sm text-slate-600">{{ $b }}</span></li>@endforeach</ol></section>
 <section class="mt-14"><h2 class="font-serif text-3xl font-semibold">Kata Mitra</h2><div class="mt-5 grid gap-4 md:grid-cols-2">
  <figure class="card"><blockquote>“Hal terbaik di 2026 adalah mulai bekerja bersama FoodLoop.”</blockquote><figcaption class="mt-2 text-sm text-slate-500">Killian Murphy, Italy Restaurant</figcaption></figure>
  <figure class="card"><blockquote>“Kami sangat menghargai kolaborasi ini dan terus membangunnya.”</blockquote><figcaption class="mt-2 text-sm text-slate-500">Ellen Woods, SMILEY Restaurant</figcaption></figure></div></section>
 <section class="mt-14 rounded-3xl bg-green-900 p-10 text-center text-white"><h2 class="font-serif text-3xl">Ready to Rescue Food?</h2><p class="mb-5 mt-2">Jadikan setiap makanan bermakna, bukan berakhir di TPA.</p><a class="rounded-full bg-lime-300 px-7 py-3 font-extrabold text-green-950" href="{{ route('report.create') }}">Report Waste</a></section>
</div>
<footer id="kontak" class="bg-green-950 py-8 text-sm text-green-100"><div class="mx-auto grid max-w-6xl gap-6 px-5 md:grid-cols-3"><div><b class="text-lime-300">FoodLoop</b><p>Logistik food rescue dan pengalihan sampah organik. Mendukung SDG 2, 11, 12, dan 13.</p></div><div><b class="text-lime-300">Kontak</b><p>Institut Teknologi Del, Laguboti, Sumatera Utara<br>foodloop@example.id</p></div><div><b class="text-lime-300">Menu</b><p><a href="{{ route('map') }}">Peta</a> · <a href="{{ route('status') }}">Laporan</a> · <a href="{{ route('impact') }}">Impact</a></p></div></div></footer>
@endsection
