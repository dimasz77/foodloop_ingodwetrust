@extends('layout')
@section('title','Beranda')
@section('content')
<section class="grid items-center gap-8 py-6 md:grid-cols-2">
 <div><h1 class="text-4xl font-extrabold leading-tight">Tutup Siklus Sampah Pangan, <span class="text-emerald-700">Berikan Impact Nyata</span></h1>
 <p class="mt-4 max-w-prose text-slate-600">Hubungkan penyedia pangan dengan penerima manfaat terdekat sebelum kedaluwarsa, dan alihkan sisa organik ke maggot BSF atau kompos agar tidak berakhir di TPA.</p>
 <div class="mt-6 flex flex-wrap gap-3"><a class="btn" href="{{ route('report.create') }}">Lapor Makanan</a><a class="btn btn-o" href="{{ route('map') }}">Lihat Peta</a></div></div>
 <div class="mx-auto grid h-64 w-64 place-items-center rounded-full bg-gradient-to-br from-emerald-300 to-emerald-700 text-center text-4xl font-black text-white" role="img" aria-label="Ilustrasi zero waste">ZERO<br>WASTE</div>
</section>
@include('_stats')
<section class="mt-12"><h2 class="mb-4 text-2xl font-bold">Interactive Rescue Map</h2>
 <div class="grid gap-4 md:grid-cols-3">
 @foreach([['📍','Peta real-time','Lihat surplus pangan terdekat: hijau layak, oranye organik, merah mendesak.'],['🧭','Klaim & rute','Klaim lalu ikuti rute sebelum waktu habis.'],['📷','Bukti penyaluran','Unggah foto serah terima sebagai validasi dampak.']] as [$i,$t,$d])
 <div class="card"><div class="text-2xl">{{ $i }}</div><b>{{ $t }}</b><p class="text-sm text-slate-600">{{ $d }}</p></div>@endforeach</div>
 <a class="btn mt-4" href="{{ route('map') }}">Buka Peta Sekarang</a></section>
<section class="mt-12"><h2 class="mb-4 text-2xl font-bold">How FoodLoop Works?</h2>
 <ol class="grid gap-4 md:grid-cols-4">
 @foreach([['Laporkan','Penyedia melaporkan surplus kurang dari 1 menit.'],['Ditemukan','Relawan atau penerima melihat dan mengklaim.'],['Dijemput','Relawan menjemput lewat rute terdekat.'],['Disalurkan','Makanan diserahkan, dampak tercatat.']] as $i => [$t,$d])
 <li class="card"><span class="text-3xl font-extrabold text-emerald-700">{{ $i+1 }}</span><b class="block">{{ $t }}</b><span class="text-sm text-slate-600">{{ $d }}</span></li>@endforeach</ol></section>
<section class="mt-12"><h2 class="mb-4 text-2xl font-bold">Kata Mitra</h2><div class="grid gap-4 md:grid-cols-2">
 <figure class="card"><blockquote>“Hal terbaik di 2026 adalah mulai bekerja bersama FoodLoop.”</blockquote><figcaption class="mt-2 text-sm text-slate-500">Killian Murphy, Italy Restaurant</figcaption></figure>
 <figure class="card"><blockquote>“Kami sangat menghargai kolaborasi ini dan terus membangunnya.”</blockquote><figcaption class="mt-2 text-sm text-slate-500">Ellen Woods, SMILEY Restaurant</figcaption></figure></div></section>
<section class="mt-12 rounded-2xl bg-emerald-800 p-8 text-center text-white"><h2 class="text-2xl font-bold">Ready to Rescue Food?</h2><p class="mb-4">Jadikan setiap makanan bermakna, bukan berakhir di TPA.</p><a class="btn !bg-white !text-emerald-900" href="{{ route('report.create') }}">Report Waste</a></section>
@endsection
