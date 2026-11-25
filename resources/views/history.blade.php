@extends('layout')
@section('title','Riwayat & Lencana')
@section('content')
@php
  $por = $items->sum('portions');
  $co2 = \App\Services\ImpactCalculator::co2e($items->sum('weight_kg'));
  $j = $journey;
  $unlocked = collect($j['badges'])->where('unlocked', true)->count();
@endphp
<x-page-hero image="riwayat.jpg" eyebrow="Riwayat & Lencana" title="Jejak Kebaikan Anda" subtitle="Ringkasan kontribusi Anda, level penyelamat pangan, dan lencana yang sudah maupun belum terbuka." :crumbs="['Riwayat & Lencana' => null]">
 <span class="chip bg-lime-300 px-3 py-1 text-[#0c1f19]">Level: {{ $j['level'] }}</span><span class="chip bg-white/15 px-3 py-1 text-white ring-1 ring-white/20">{{ $unlocked }}/{{ count($j['badges']) }} lencana</span>
</x-page-hero>
<div class="pull-up mx-auto max-w-6xl px-4">

 {{-- Level --}}
 <section class="grid items-center gap-6 overflow-hidden rounded-3xl bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] p-6 text-white shadow-xl md:grid-cols-[auto_1fr]" data-reveal>
  <div class="relative mx-auto grid h-32 w-32 place-items-center">
   <svg viewBox="0 0 36 36" class="absolute inset-0 -rotate-90" aria-hidden="true"><circle cx="18" cy="18" r="15.9" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="3"/><circle cx="18" cy="18" r="15.9" fill="none" stroke="#bef264" stroke-width="3" stroke-linecap="round" stroke-dasharray="{{ $j['progress'] }} 100" class="transition-all duration-1000"/></svg>
   <div class="text-center"><div class="text-4xl">🏅</div><b class="text-xs">{{ $j['progress'] }}%</b></div>
  </div>
  <div><span class="chip bg-white/15">Level Anda</span><h2 class="mt-1 text-2xl font-extrabold">{{ $j['level'] }}</h2>
   <p class="text-xs text-white/80">@if($j['next'])Salurkan <b class="text-lime-300">{{ number_format($j['toNext'], 1, ',', '.') }} kg</b> lagi untuk mencapai <b>{{ $j['next'] }}</b>.@else Anda telah mencapai level tertinggi. Luar biasa! 🎉@endif</p>
   <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/15"><div class="h-full rounded-full bg-lime-300 transition-all duration-1000" style="width:{{ $j['progress'] }}%"></div></div>
   <div class="mt-4 grid grid-cols-3 gap-3 text-center">
    @foreach([[$por, 0, 'Porsi'], [$j['kg'], 1, 'kg tersalurkan'], [$co2, 1, 'kg CO₂e dicegah']] as [$v, $d, $l])<div class="rounded-xl bg-white/10 p-2"><b class="text-lg" data-count="{{ $v }}" data-decimals="{{ $d }}">{{ number_format($v, $d, ',', '.') }}</b><div class="text-[0.6875rem] text-white/70">{{ $l }}</div></div>@endforeach
   </div></div>
 </section>

 {{-- Lencana --}}
 <section class="mt-8"><div class="flex items-end justify-between" data-reveal><h2 class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Lencana Pencapaian</h2><span class="chip bg-moss/10 text-moss">{{ $unlocked }}/{{ count($j['badges']) }} terbuka</span></div>
  <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-6">
   @foreach($j['badges'] as $b)
    <div class="group relative rounded-2xl p-4 text-center ring-1 transition {{ $b['unlocked'] ? 'bg-white ring-moss/20 hover:-translate-y-1 hover:shadow-lg' : 'bg-slate-50 ring-black/5' }}" data-reveal style="transition-delay:{{ $loop->index * 60 }}ms">
     <div class="text-3xl transition group-hover:scale-110 {{ $b['unlocked'] ? '' : 'opacity-30 grayscale' }}">{{ $b['icon'] }}</div>
     <b class="mt-1 block text-[0.75rem] {{ $b['unlocked'] ? '' : 'text-slate-400' }}">{{ $b['name'] }}</b><span class="text-[0.6875rem] text-slate-500">{{ $b['desc'] }}</span>
     @unless($b['unlocked'])<span class="absolute right-2 top-2 text-xs" aria-label="Terkunci">🔒</span>@endunless
    </div>
   @endforeach
  </div></section>

 {{-- Jejak --}}
 <section class="mt-10" x-data="{f:'all',n:6}">
  <div class="flex flex-wrap items-end justify-between gap-3" data-reveal><div><h2 class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Riwayat Penyaluran</h2><p class="text-[0.75rem] text-slate-500">{{ $items->count() }} penyaluran telah selesai.</p></div>
   <div class="inline-flex rounded-full bg-white p-1 text-[0.75rem] font-bold shadow-sm ring-1 ring-black/5" role="tablist">@foreach(['all' => 'Semua', 'layak' => '🍱 Layak', 'organik' => '♻️ Organik'] as $k => $l)<button role="tab" :aria-selected="f=='{{ $k }}'" @click="f='{{ $k }}';n=6" class="rounded-full px-3 py-1.5 transition" :class="f=='{{ $k }}'?'bg-moss text-white':'text-slate-500'">{{ $l }}</button>@endforeach</div></div>
  <ul class="mt-3 space-y-3">
   @forelse($items as $idx => $h)
    <li x-show="f=='all' ? {{ $idx }} < n : f=='{{ $h->category }}'" x-transition.opacity class="card flex flex-wrap items-center justify-between gap-3 !py-3">
     <span class="flex min-w-0 items-center gap-3"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $h->category == 'layak' ? 'bg-green-50' : 'bg-amber-50' }} text-lg">{{ $h->category == 'layak' ? '🍞' : '🌱' }}</span>
      <span class="min-w-0 text-xs"><b>{{ $h->title }}</b> · {{ $h->provider_name }}<br><span class="text-slate-500">{{ $h->delivered_at?->locale('id')->translatedFormat('d M Y') }} · {{ $h->partner?->name ?? '-' }}</span> <span class="chip {{ $h->category == 'layak' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">{{ $h->category == 'layak' ? 'BERHASIL DISALURKAN' : 'DIKOMPOSKAN' }}</span>
       @if($h->thank_note)<span class="mt-1 block italic text-slate-500">💌 “{{ $h->thank_note }}”</span>@endif</span></span>
     <span class="flex items-center gap-2"><span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-moss">{{ $h->category == 'layak' ? '+'.$h->portions.' Porsi' : '+'.$h->weight_kg.' kg' }}</span><a href="{{ route('status', ['id' => $h->id]) }}" class="text-xs font-bold text-slate-400 hover:text-moss" aria-label="Detail {{ $h->title }}">›</a></span>
    </li>
   @empty
    <li class="card py-10 text-center text-xs text-slate-500"><div class="text-5xl">🌱</div><p class="mt-2">Belum ada penyaluran selesai. Lencana pertama Anda menunggu!</p>
     <a class="btn btn-g mt-3" href="{{ auth()->user()->role == 'provider' ? route('report.create') : route('map') }}">{{ auth()->user()->role == 'provider' ? '➕ Buat Laporan' : '📍 Cari Makanan' }}</a></li>
   @endforelse
  </ul>
  @if($items->count() > 6)<div class="mt-4 text-center" x-show="f=='all' && n < {{ $items->count() }}"><button class="btn btn-w" @click="n+=6">Lihat Lebih Banyak ⌄</button></div>@endif
 </section>
</div>
@endsection
