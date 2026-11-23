@extends('layout')
@section('title','Status Laporan')
@section('content')
<div class="flex flex-wrap items-center justify-between gap-2"><h1 class="text-3xl font-extrabold">Status Laporan</h1>
 <a class="btn btn-o" href="{{ route('status', $all ? [] : ['all'=>1]) }}">{{ $all ? 'Tampilkan laporan saya' : 'Tampilkan semua laporan (demo juri)' }}</a></div>
<div class="mt-6 grid gap-4 md:grid-cols-2">
@forelse($reports as $r)
 <article class="card"><div class="flex gap-2 text-xs"><span class="rounded-full bg-slate-100 px-2 py-1">{{ $r->category=='layak'?'Human-First':'Earth-First' }}</span>@if($r->rerouted)<span class="rounded-full bg-amber-100 px-2 py-1">Dialihkan otomatis</span>@endif</div>
 <h2 class="mt-2 text-lg font-bold">{{ $r->title }}</h2><p class="text-sm text-slate-500">{{ $r->provider_name }} · {{ $r->portions ? $r->portions.' porsi · ' : '' }}{{ $r->weight_kg }} kg</p>
 <ol class="my-4 flex justify-between text-center text-xs" aria-label="Tahap status">@foreach(['Menunggu Klaim','Dalam Perjalanan','Tersalurkan'] as $i=>$s)@php $at=[0=>0,1=>0,2=>1,3=>2]; @endphp<li class="flex-1 {{ $i <= ($r->status=='waiting'?0:($r->status=='delivered'?2:1)) ? 'font-bold text-emerald-700' : 'text-slate-400' }}"><span class="mx-auto mb-1 block h-3 w-3 rounded-full bg-current"></span>{{ $s }}</li>@endforeach</ol>
 <div class="rounded-lg bg-slate-100 p-3 text-center font-mono text-xl" data-exp="{{ $r->expires_at->toIso8601String() }}">--:--:--</div>
 <p class="mt-2 text-sm text-slate-600">Penerima/pengolah: <b>{{ $r->partner?->name ?? '-' }}</b> · Kontribusi potensial: {{ $r->portions }} porsi, {{ number_format($r->co2e,1,',','.') }} kg CO₂e.</p>
 <div class="mt-3 flex flex-wrap gap-2">
  @if($r->status=='waiting')<form method="POST" action="{{ route('report.act',[$r,'claim']) }}">@csrf<button class="btn">Klaim (simulasi)</button></form>
   @if($r->category=='layak')<form method="POST" action="{{ route('report.act',[$r,'reroute']) }}">@csrf<button class="btn btn-o">Simulasi Auto Re-route</button></form>@endif @endif
  @if($r->status=='claimed')<form method="POST" action="{{ route('report.act',[$r,'start']) }}">@csrf<button class="btn">Mulai Antar</button></form>@endif
  @if(in_array($r->status,['claimed','on_the_way']))<form method="POST" action="{{ route('report.act',[$r,'complete']) }}" enctype="multipart/form-data" class="flex flex-wrap gap-2">@csrf<input type="file" name="proof" accept="image/*" required class="text-sm" aria-label="Foto bukti"><button class="btn">Selesaikan + Bukti Foto</button></form>@endif
 </div></article>
@empty<div class="card md:col-span-2">Belum ada laporan aktif. <a class="font-semibold text-emerald-700" href="{{ route('report.create') }}">Buat laporan</a> atau tampilkan semua laporan demo.</div>@endforelse
</div>
<h2 class="mt-10 text-2xl font-bold">Riwayat Kontribusi</h2>
<div class="mt-4 grid gap-4 md:grid-cols-2"><div class="card"><b class="text-2xl text-emerald-700">{{ $history->sum('portions') }}</b> porsi disalurkan</div><div class="card"><b class="text-2xl text-emerald-700">{{ number_format(\App\Services\ImpactCalculator::co2e($history->sum('weight_kg')),1,',','.') }}</b> kg CO₂e dicegah</div></div>
<ul class="mt-4 space-y-2">@forelse($history as $h)<li class="card flex flex-wrap items-center justify-between gap-2 py-3"><span><b>{{ $h->title }}</b> · {{ $h->provider_name }}<br><small class="text-slate-500">{{ $h->delivered_at?->translatedFormat('d M Y H:i') }} · {{ $h->partner?->name }}</small></span><span class="text-sm">{{ $h->category=='layak' ? '+'.$h->portions.' porsi' : '+'.$h->weight_kg.' kg' }} @if($h->proof_path)<a class="text-emerald-700 underline" target="_blank" href="{{ asset('storage/'.$h->proof_path) }}">bukti</a>@endif</span></li>@empty<li class="card">Belum ada riwayat.</li>@endforelse</ul>
@push('scripts')<script>
const hms=m=>{m=Math.max(0,m)/1000|0;return [m/3600|0,m%3600/60|0,m%60].map(v=>String(v).padStart(2,'0')).join(':')};
setInterval(()=>document.querySelectorAll('[data-exp]').forEach(n=>n.textContent=hms(new Date(n.dataset.exp)-Date.now())),1000);
</script>@endpush
@endsection
