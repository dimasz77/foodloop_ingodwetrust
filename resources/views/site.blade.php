@extends('layout')
@section('shell')
<header class="sticky top-0 z-[1000] border-b border-black/5 bg-white/90 backdrop-blur"><div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
 <a href="{{ route('home') }}" class="font-extrabold text-green-800">🌿 FoodLoop</a>
 <nav class="flex gap-1 text-sm" aria-label="Navigasi utama">@foreach(['home'=>'Home','map'=>'Map','status'=>'Laporan','impact'=>'Impact'] as $r=>$l)<a href="{{ route($r) }}" @if(request()->routeIs($r)) aria-current="page" @endif class="rounded-full px-3 py-1.5 {{ request()->routeIs($r)?'bg-lime-200 font-bold text-green-900':'text-slate-600 hover:bg-slate-100' }}">{{ $l }}</a>@endforeach
 <a href="{{ route('report.create') }}" class="btn ml-2 !py-1.5">Lapor</a></nav></div></header>
@yield('content')
@endsection
