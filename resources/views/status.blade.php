@extends('layout')
@section('title','Status Laporan')
@section('content')
<div class="mx-auto max-w-5xl px-4 pt-8">
@if(!$cur)<div class="card">@if(auth()->user()->role=='provider')Belum ada laporan. <a class="font-bold text-moss underline" href="{{ route('report.create') }}">Buat laporan</a>.@else Belum ada klaim. <a class="font-bold text-moss underline" href="{{ route('map') }}">Buka peta</a> untuk mengklaim makanan.@endif</div>
@else
@php $r=$cur; $d=$r->status=='delivered'; $left=max(0,now()->diffInSeconds($r->expires_at,false)); $step=$r->status=='waiting'?0:($d?2:1); $km=$r->partner?\App\Models\Partner::km($r->lat,$r->lng,$r->partner->lat,$r->partner->lng):null; @endphp
@if($demo)<p class="mb-3 rounded-lg bg-amber-50 p-2 text-[11px] text-amber-800">Mode demo: menampilkan data contoh. Buat laporan Anda sendiri agar halaman ini menampilkan laporan Anda.</p>@endif
<div class="grid items-center gap-6 md:grid-cols-[1.4fr_1fr]"><div>
 <h1 class="h2 text-moss">{{ $d?'Laporan Selesai':'Laporan Sedang Diproses' }}</h1><p class="text-xs text-slate-600">{{ $d?'Terimakasih atas kontribusi Anda!':'Terimakasih sudah melaporkan: '.$r->title }}</p>
 <h2 class="mt-5 text-sm font-extrabold">Kontribusi Anda berpotensial :</h2>
 <ul class="mt-2 space-y-2 text-xs"><li class="flex gap-3"><span class="text-moss">✔</span><span><b class="text-sm">{{ $r->portions }} Porsi</b><br><span class="text-slate-500">Diselamatkan/dikelola</span></span></li><li class="flex gap-3"><span class="text-moss">✔</span><span><b class="text-sm">{{ number_format($r->co2e,1,',','.') }} kg CO2</b><br><span class="text-slate-500">Dicegah</span></span></li></ul></div>
 <div class="mx-auto grid h-40 w-40 place-items-center rounded-full bg-gradient-to-br from-green-200 to-green-700 text-7xl" role="img" aria-label="Daur ulang">♻️</div></div>
@if($r->status=='waiting')<h2 class="mt-8 text-sm font-extrabold">Mendekati Batas Waktu</h2><p class="text-xs text-slate-600">Saat ini belum ada relawan yang mengambil. Anda akan diberitahu saat relawan tiba.</p>
 <p class="mt-2 text-xs">Sisa Waktu : <span class="rounded bg-slate-200 px-2 py-1 font-mono font-bold" data-exp="{{ $r->expires_at->toIso8601String() }}">--:--:--</span></p>
 <h2 class="mt-6 text-sm font-extrabold">Auto Re-route ke Jalur Organik</h2><p class="text-xs text-slate-600">Jika tidak ada yang mengambil tepat waktu, laporan otomatis dialihkan ke jalur organik.</p>@endif
@if($r->rerouted)<p class="mt-3 rounded-lg bg-amber-100 p-2 text-xs font-bold text-amber-900">Laporan ini sudah dialihkan otomatis ke jalur organik.</p>@endif
<div class="mt-6 grid h-52 place-items-center rounded-xl bg-gradient-to-br from-green-200 to-lime-100 text-8xl" role="img" aria-label="Mitra terdekat">{{ $r->category=='layak'?'🏡':'🌱' }}</div>
<p class="mt-2 text-center text-[11px] text-slate-500">Mitra FoodLoop terdekat</p><p class="text-center text-sm font-extrabold">{{ $r->partner?->name ?? '-' }}</p><p class="text-center text-[11px] text-slate-500">{{ $km!==null?($km<1?round($km*1000).' m':number_format($km,1,',','.').' km').' dari lokasi Anda':'' }}</p>
<ol class="mx-auto mt-6 flex max-w-md items-start justify-between text-center text-[11px]" aria-label="Tahap status">@foreach(['Menunggu Klaim','Dalam Perjalanan','Tersalurkan'] as $i=>$s)<li class="flex-1 {{ $i<=$step?'font-bold text-moss':'text-slate-400' }}"><span class="relative mx-auto mb-1 block h-3.5 w-3.5 rounded-full bg-current">@if($i<2)<span class="absolute left-3.5 top-1.5 h-0.5 w-[calc(100%+6rem)] max-w-[9rem] bg-current"></span>@endif</span>{{ $s }}</li>@endforeach</ol>
@if($r->volunteer_name)<div class="mt-6 text-center"><p class="text-xs">Makanan Anda {{ $d?'sudah tersalurkan':'sedang diambil' }} oleh :</p><div class="mt-2 grid place-items-center"><span class="grid h-14 w-14 place-items-center rounded-full bg-slate-200 text-2xl">🧑🏽</span></div><p class="mt-1 text-sm font-extrabold">{{ $r->volunteer_name }} <span class="text-moss">●</span></p><button type="button" class="btn mt-2" onclick="alert('Demo: kontak relawan tidak tersedia pada data contoh.')">Kontak Pengirim</button></div>@endif
@if($d&&$r->proof_path)<p class="mt-3 text-center text-xs"><a class="font-bold text-moss underline" target="_blank" href="{{ asset('storage/'.$r->proof_path) }}">Lihat bukti penyaluran</a></p>@endif
<section class="card mt-8"><h3 class="text-sm font-extrabold">Panel Demo Juri</h3><p class="text-[11px] text-slate-500">Ubah status untuk menguji alur end-to-end.</p><div class="mt-2 flex flex-wrap gap-2">
 @if($r->status=='waiting' && auth()->user()->role=='provider')@if($r->category=='layak')<form method="POST" action="{{ route('report.act',[$r,'reroute']) }}">@csrf<button class="btn btn-w">Simulasi Auto Re-route</button></form>@endif @endif
 @if($r->status=='claimed' && auth()->user()->role=='recipient')<form method="POST" action="{{ route('report.act',[$r,'start']) }}">@csrf<button class="btn">Mulai Antar</button></form>@endif
 @if(in_array($r->status,['claimed','on_the_way']) && auth()->user()->role=='recipient')<form method="POST" action="{{ route('report.act',[$r,'complete']) }}" enctype="multipart/form-data" class="flex flex-wrap gap-2">@csrf<input type="file" name="proof" accept="image/*" required class="text-xs" aria-label="Foto bukti"><button class="btn">Selesaikan + Upload Bukti</button></form>@endif
 @if($d)<span class="text-xs text-slate-500">Laporan sudah tersalurkan.</span>@endif</div>
 <div class="mt-3 flex flex-wrap gap-1 text-[11px]">@foreach($others as $o)<a href="{{ route('status',['id'=>$o->id]) }}" class="rounded-full border px-2 py-1 {{ $o->id==$r->id?'bg-teal2 text-white':'bg-white' }}">{{ \Illuminate\Support\Str::limit($o->title,22) }}</a>@endforeach</div></section>
@endif</div>
@push('scripts')<script>
const hms=m=>{m=Math.max(0,m)/1000|0;return [m/3600|0,m%3600/60|0,m%60].map(v=>String(v).padStart(2,'0')).join(':')};
setInterval(()=>document.querySelectorAll('[data-exp]').forEach(n=>n.textContent=hms(new Date(n.dataset.exp)-Date.now())),1000);
</script>@endpush
@endsection
