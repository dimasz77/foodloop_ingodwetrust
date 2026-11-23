@extends('dash')
@section('title','Laporan')
@section('content')
@php $act=$reports->whereIn('status',['claimed','on_the_way']); $feed=$act->merge($history->take(3)); @endphp
<div class="flex flex-col gap-4 rounded-2xl bg-gradient-to-r from-slate-200 to-emerald-100 p-6 md:flex-row md:items-center md:justify-between"><div><h1 class="text-2xl font-extrabold">Pusat Laporan Pangan</h1><p class="max-w-md text-sm text-slate-600">Pantau surplus, klaim, dan penyaluran. Kurangi sampah, hemat biaya, dan beri dampak nyata.</p></div>
 <div class="flex gap-2"><a class="btn btn-o" href="{{ route('report.create') }}">Tambah Laporan</a><a class="btn" href="{{ route('impact') }}">Lihat Impact</a></div></div>
<div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
 @foreach([['Laporan Aktif',$reports->count(),'laporan','bg-emerald-50 border-emerald-200'],['Menunggu Klaim',$reports->where('status','waiting')->count(),'laporan','bg-amber-50 border-amber-200'],['Tersalurkan',$history->count(),'laporan','bg-sky-50 border-sky-200'],['CO₂e Dicegah',number_format(\App\Services\ImpactCalculator::co2e($history->sum('weight_kg')),1,',','.'),'kg','bg-rose-50 border-rose-200']] as [$l,$v,$u,$c])
 <div class="rounded-2xl border p-4 {{ $c }}"><div class="text-sm font-semibold">{{ $l }}</div><div class="mt-2 text-3xl font-extrabold">{{ $v }} <span class="text-base font-medium text-slate-500">{{ $u }}</span></div></div>@endforeach</div>
<div class="mt-4 grid gap-4 lg:grid-cols-3">
 <section class="card lg:col-span-2"><div class="flex items-center justify-between"><div><h2 class="text-lg font-extrabold">Inventori Surplus</h2><p class="text-sm text-slate-500">{{ $all?'Semua laporan (mode demo juri)':'Laporan Anda yang masih aktif' }}</p></div><a class="text-sm font-bold text-green-800 underline" href="{{ route('status', $all?[]:['all'=>1]) }}">{{ $all?'Laporan saya':'Tampilkan semua' }}</a></div>
 <ul class="mt-3 space-y-3">@forelse($reports as $r)
  <li class="rounded-xl border border-slate-200 p-3"><div class="flex flex-wrap items-center justify-between gap-2"><div><b>{{ $r->title }}</b><div class="text-sm text-slate-500">{{ $r->provider_name }} · {{ $r->portions?$r->portions.' porsi · ':'' }}{{ $r->weight_kg }} kg · {{ $r->partner?->name }}</div></div>
   <div class="flex items-center gap-2 text-sm"><span class="font-mono" data-exp="{{ $r->expires_at->toIso8601String() }}">--:--:--</span>
   <span class="rounded-full px-2 py-1 text-xs font-bold {{ $r->category=='organik'?'bg-amber-100 text-amber-800':($r->urgent_now?'bg-red-500 text-white':'bg-slate-100') }}">{{ $r->category=='organik'?'Organik':($r->urgent_now?'Mendesak':'Siap') }}</span></div></div>
   <div class="mt-2 text-xs text-slate-500">Status: <b>{{ $r->status_label }}</b>@if($r->rerouted) · dialihkan otomatis ke jalur organik @endif</div>
   <div class="mt-2 flex flex-wrap gap-2">
    @if($r->status=='waiting')<form method="POST" action="{{ route('report.act',[$r,'claim']) }}">@csrf<button class="btn !py-1.5">Klaim (simulasi)</button></form>@if($r->category=='layak')<form method="POST" action="{{ route('report.act',[$r,'reroute']) }}">@csrf<button class="btn btn-o !py-1.5">Simulasi Auto Re-route</button></form>@endif @endif
    @if($r->status=='claimed')<form method="POST" action="{{ route('report.act',[$r,'start']) }}">@csrf<button class="btn !py-1.5">Mulai Antar</button></form>@endif
    @if(in_array($r->status,['claimed','on_the_way']))<form method="POST" action="{{ route('report.act',[$r,'complete']) }}" enctype="multipart/form-data" class="flex flex-wrap gap-2">@csrf<input type="file" name="proof" accept="image/*" required class="text-xs" aria-label="Foto bukti"><button class="btn !py-1.5">Selesaikan + Bukti Foto</button></form>@endif</div></li>
 @empty<li class="rounded-xl bg-slate-50 p-4 text-sm">Belum ada laporan aktif. <a class="font-bold text-green-800 underline" href="{{ route('report.create') }}">Buat laporan</a> atau tampilkan semua laporan demo.</li>@endforelse</ul></section>
 <section class="card"><h2 class="text-lg font-extrabold">Redistribusi Aktif</h2><p class="text-sm text-slate-500">Donasi yang sedang berjalan</p>
 <ul class="mt-3 space-y-3">@forelse($feed as $r)<li class="rounded-xl border border-slate-200 p-3 text-sm"><b>{{ $r->partner?->name ?? $r->title }}</b><div class="text-xs text-slate-500">{{ $r->title }}</div><div class="mt-2 flex items-center justify-between"><span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $r->status=='delivered'?'bg-green-600 text-white':'bg-slate-100' }}">{{ $r->status=='delivered'?'✓ Tersalurkan':$r->status_label }}</span><span class="text-xs text-slate-500">{{ ($r->delivered_at ?? $r->updated_at)->diffForHumans() }}</span></div></li>@empty<li class="text-sm text-slate-500">Belum ada.</li>@endforelse</ul></section>
</div>
<section class="card mt-4"><h2 class="text-lg font-extrabold">Quick Actions</h2><div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
 @foreach([['report.create','Tambah Laporan','＋'],['map','Buka Peta','📍'],['impact','Impact Analysis','📈']] as [$r,$l,$i])<a href="{{ route($r) }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-3 text-sm font-semibold hover:bg-slate-50">{{ $l }} <span>{{ $i }}</span></a>@endforeach
 <a href="{{ route('status', $all?[]:['all'=>1]) }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-3 text-sm font-semibold hover:bg-slate-50">{{ $all?'Laporan Saya':'Semua Laporan (Demo)' }} <span>👥</span></a></div></section>
<section class="card mt-4"><h2 class="text-lg font-extrabold">Riwayat Kontribusi</h2>
 <div class="mt-3 grid gap-3 sm:grid-cols-2"><div class="rounded-xl bg-emerald-50 p-3"><b class="text-2xl text-green-800">{{ $history->sum('portions') }}</b> porsi disalurkan</div><div class="rounded-xl bg-emerald-50 p-3"><b class="text-2xl text-green-800">{{ number_format(\App\Services\ImpactCalculator::co2e($history->sum('weight_kg')),1,',','.') }}</b> kg CO₂e dicegah</div></div>
 <ul class="mt-3 space-y-2">@forelse($history as $h)<li class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 p-3 text-sm"><span><b>{{ $h->title }}</b> · {{ $h->provider_name }}<br><small class="text-slate-500">{{ $h->delivered_at?->format('d M Y H:i') }} · {{ $h->partner?->name }}</small></span><span>{{ $h->category=='layak'?'+'.$h->portions.' porsi':'+'.$h->weight_kg.' kg' }} @if($h->proof_path)<a class="text-green-800 underline" target="_blank" href="{{ asset('storage/'.$h->proof_path) }}">bukti</a>@endif</span></li>@empty<li class="text-sm text-slate-500">Belum ada riwayat.</li>@endforelse</ul></section>
@push('scripts')<script>
const hms=m=>{m=Math.max(0,m)/1000|0;return [m/3600|0,m%3600/60|0,m%60].map(v=>String(v).padStart(2,'0')).join(':')};
setInterval(()=>document.querySelectorAll('[data-exp]').forEach(n=>n.textContent=hms(new Date(n.dataset.exp)-Date.now())),1000);
</script>@endpush
@endsection
