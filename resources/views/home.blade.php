@extends('layout')
@section('title','Beranda')
@section('content')
<section class="mx-auto max-w-5xl px-4 pt-10">
 <div class="grid items-center gap-6 text-center md:grid-cols-[1fr_2fr_1fr]">
  <div class="mx-auto grid h-40 w-40 place-items-center rounded-full bg-amber-100 text-7xl" role="img" aria-label="Ilustrasi sisa makanan">🥘</div>
  <div><h1 class="text-2xl font-extrabold leading-tight md:text-3xl">Tutup Siklus Sampah Pangan,<br><span class="text-moss">Berikan Impact Nyata</span></h1>
   <p class="mx-auto mt-3 max-w-md text-xs text-slate-600">Bergabunglah dengan ribuan Hero di seluruh Indonesia dalam misi menyelamatkan makanan layak dan mengolah sisa makanan menjadi energi.</p>
   <div class="mt-4 flex justify-center gap-2"><a class="btn" href="{{ route('report.create') }}">Report Waste</a><a class="btn" href="{{ route('map') }}">Join as Partner</a></div>
   <div class="mt-4 flex items-center justify-center gap-2 text-[11px] text-slate-500"><span class="flex -space-x-2">@foreach(['🧑🏽','👩🏻','🧔🏾'] as $a)<span class="grid h-7 w-7 place-items-center rounded-full bg-white ring-2 ring-cream">{{ $a }}</span>@endforeach</span>Bergabung bersama 2.450+ penyelamat pangan di seluruh Indonesia.</div></div>
  <div class="mx-auto grid h-36 w-36 place-items-center rounded-[40%_60%_55%_45%] bg-green-800 text-center font-serif text-2xl font-bold leading-none text-white" role="img" aria-label="Zero waste">ZERO<br>WASTE</div></div>
</section>
<section class="mx-auto mt-16 grid max-w-5xl items-center gap-8 px-4 md:grid-cols-2">
 <div class="relative h-64 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-100 to-sky-100 ring-1 ring-black/5" role="img" aria-label="Pratinjau peta"><div class="absolute left-3 top-3 rounded-lg bg-white px-3 py-1.5 text-[10px] text-slate-500 shadow">🔍 Cari lokasi atau jenis makanan…</div>
  @foreach([[30,40,'#1f6b3a'],[48,55,'#d9363e'],[62,30,'#f28c28'],[40,70,'#1f6b3a'],[70,60,'#d9363e']] as [$x,$y,$c])<span class="absolute h-4 w-4 rounded-full border-2 border-white shadow" style="left:{{ $x }}%;top:{{ $y }}%;background:{{ $c }}"></span>@endforeach</div>
 <div><h2 class="h2">Interactive Rescue Map</h2><p class="mt-1 text-xs text-slate-600">Temukan makanan surplus atau sisa disekitar Anda dan ambil peran dalam menyelamatkannya.</p>
  <ul class="mt-4 space-y-3 text-xs">@foreach([['📍','Peta Interaktif real-time','Lihat lokasi makanan surplus terdekat'],['📷','Bukti Penyaluran','Unggah foto bukti serah terima'],['🧭','Klaim & Route Otomatis','Navigasi tercepat menuju lokasi penjemputan']] as [$i,$a,$b])<li class="flex gap-3"><span class="text-lg">{{ $i }}</span><span><b>{{ $a }}</b><br><span class="text-slate-500">{{ $b }}</span></span></li>@endforeach</ul>
  <a class="btn mt-4" href="{{ route('map') }}">Buka Peta Sekarang ›</a></div>
</section>
<section class="mx-auto mt-16 grid max-w-5xl gap-6 px-4 md:grid-cols-[1fr_2fr]"><h2 class="h2">Creating Impact,<br>One <span class="text-moss">Meal</span> at a Time.</h2>
 <div><p class="text-xs text-slate-600">Setiap kilogram makanan yang diselamatkan berarti lebih banyak porsi pangan, lebih sedikit limbah, dan emisi karbon yang berhasil dicegah.</p>
 <div class="mt-4 grid gap-4 sm:grid-cols-3">@foreach([[number_format($t['kg'],0,',','.').' kg','Food Rescued','Makanan berhasil diselamatkan'],[number_format($t['meals'],0,',','.'),'Meal Distributed','Porsi makanan disalurkan'],[number_format($t['co2_ton'],2,',','.').' Ton','CO2 Prevented','Estimasi emisi dicegah']] as [$v,$a,$b])<div class="border-l-2 border-slate-300 pl-3"><b class="text-xl">{{ $v }}</b><div class="text-[11px] font-bold">{{ $a }}</div><div class="text-[10px] text-slate-500">{{ $b }}</div></div>@endforeach</div></div></section>
<section class="mx-auto mt-16 max-w-5xl px-4"><h2 class="h2 text-center">How <span class="text-moss">FoodLoop</span> Works?</h2><ol class="mt-6 grid gap-6 sm:grid-cols-4">
 @foreach([['Laporkan','Penyedia melaporkan makanan surplus kurang dari 1 menit'],['Ditemukan','Relawan/penerima melihat di peta dan mengklaim makanan'],['Dijemput','Relawan menjemput makanan dengan rute terdekat'],['Disalurkan','Makanan disalurkan kepada penerima manfaat']] as $i=>[$a,$b])<li class="flex gap-3"><span class="font-serif text-4xl text-slate-500">{{ $i+1 }}</span><span class="text-xs"><b>{{ $a }}</b><br><span class="text-slate-500">{{ $b }}</span></span></li>@endforeach</ol></section>
<section class="mt-16 bg-gradient-to-br from-[#1b4747] to-[#2f6862] px-4 py-14 text-white"><div class="mx-auto grid max-w-5xl items-center gap-6 md:grid-cols-2"><div><h2 class="text-2xl font-extrabold">Every Excess Food ‖<br>Real Impact.</h2><p class="mt-3 text-xs text-white/80">FoodLoop connects restaurants, hotels, catering services, and communities to rescue excess food before it becomes waste. Every successful pickup helps feed people, reduce food waste, and lower carbon emissions.</p></div><div class="text-center text-8xl" role="img" aria-label="Keranjang sayur">🧺🥦🍅</div></div></section>
<section class="mx-auto max-w-5xl px-4 py-12"><div class="flex items-center gap-4"><span class="text-xs font-bold">Potential Partners</span><span class="rounded-lg bg-blue-900 px-4 py-2 font-extrabold text-white">del</span></div>
 <h2 class="h2 mt-8 text-center">What Partners Say About Working with Us</h2>
 <div class="mt-6 grid gap-4 md:grid-cols-2">@foreach([['The best thing happened to me in 2026 is to start working with FoodLoop','Killian Murphy'],['We truly appreciated the collaboration and what we’ve been building together overtime','Ellen Woods']] as [$q,$n])<figure class="card"><blockquote class="text-xs text-slate-600">“{{ $q }}”</blockquote><figcaption class="mt-3 text-xs font-bold">{{ $n }}</figcaption></figure>@endforeach</div></section>
@endsection
