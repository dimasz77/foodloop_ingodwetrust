@extends('layout')
@section('shell')
@php $dark=$__env->yieldContent('dark')==='1'; $nav=[['status','Dashboard','▦'],['report.create','Tambah Laporan','＋'],['map','Peta','📍'],['impact','Impact','📈'],['home','Beranda','🏠']]; @endphp
<div class="min-h-screen md:flex {{ $dark?'bg-[#f1efe6]':'bg-[#eef1ec]' }}">
 <aside class="hidden w-60 shrink-0 flex-col p-4 md:flex {{ $dark?'bg-[#2d6a45] text-white':'border-r border-black/5 bg-white' }}">
  <a href="{{ route('home') }}" class="mb-6 px-2 text-xl font-extrabold {{ $dark?'text-white':'text-green-800' }}">🌿 Food<span class="{{ $dark?'text-lime-300':'text-green-600' }}">Loop</span></a>
  <nav class="space-y-1" aria-label="Menu dashboard">@foreach($nav as [$r,$l,$i])<a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($r) ? ($dark?'bg-lime-300 text-green-950':'bg-green-600 text-white') : ($dark?'text-white/90 hover:bg-white/10':'text-slate-600 hover:bg-slate-100') }}"><span aria-hidden="true">{{ $i }}</span>{{ $l }}</a>@endforeach</nav>
  <div class="mt-auto rounded-2xl p-3 text-sm {{ $dark?'bg-lime-300 text-green-950':'bg-green-50 text-green-900' }}"><b>Tahukah Anda?</b><br>1 kg makanan terselamatkan ≈ {{ number_format(\App\Services\ImpactCalculator::CO2E_PER_KG,2,',','.') }} kg CO₂e dicegah.</div>
 </aside>
 <div class="min-w-0 flex-1">
  <nav class="flex gap-1 overflow-x-auto bg-white p-2 md:hidden" aria-label="Menu">@foreach($nav as [$r,$l,$i])<a href="{{ route($r) }}" class="whitespace-nowrap rounded-full px-3 py-1.5 text-sm {{ request()->routeIs($r)?'bg-green-700 font-bold text-white':'text-slate-600' }}">{{ $l }}</a>@endforeach</nav>
  <div class="mx-auto max-w-6xl p-4 md:p-6">@include('_flash')@yield('content')</div>
 </div>
</div>
@endsection
