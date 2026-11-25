{{--
  Header halaman bergaya Beranda: latar gelap/foto, breadcrumb, judul besar.
  Foto opsional: public/images/{image}. Slot dipakai untuk aksi/status di bawah judul.
--}}
@props(['title', 'eyebrow' => null, 'subtitle' => null, 'image' => null, 'crumbs' => [], 'compact' => false])
@php $img = $image && file_exists(public_path('images/'.$image)) ? asset('images/'.$image) : null; @endphp
<section class="relative isolate overflow-hidden bg-[#0c1f19] text-white">
 @if($img)
  <img src="{{ $img }}" alt="" class="absolute inset-0 -z-10 h-full w-full animate-kenburns object-cover">
  <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#0c1f19]/90 via-[#0c1f19]/65 to-[#0c1f19]/30"></div>
 @else
  <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#0c1f19] via-[#143a2c] to-[#1f4b4a]"><x-contour /></div>
 @endif
 <div class="mx-auto max-w-6xl px-4 {{ isset($after) ? 'pb-14 pt-8 md:pt-10' : ($compact ? 'pb-20 pt-8' : 'pb-24 pt-8 md:pb-28 md:pt-10') }}">
  <nav aria-label="Breadcrumb" class="animate-rise text-[0.75rem] font-semibold text-white/60">
   <ol class="flex flex-wrap items-center gap-1.5">
    <li><a href="{{ route('home') }}" class="hover:text-lime-300">Beranda</a></li>
    @foreach($crumbs as $label => $url)
     <li aria-hidden="true">/</li>
     <li>@if($url)<a href="{{ $url }}" class="hover:text-lime-300">{{ $label }}</a>@else<span class="text-white" aria-current="page">{{ $label }}</span>@endif</li>
    @endforeach
   </ol>
  </nav>
  @if($eyebrow)<p class="mt-6 flex animate-rise items-center gap-3 text-[0.75rem] font-extrabold uppercase tracking-[.2em] text-lime-300 [animation-delay:.05s]"><span class="h-[3px] w-8 rounded bg-lime-300"></span>{{ $eyebrow }}</p>@endif
  <h1 class="{{ $eyebrow ? 'mt-3' : 'mt-6' }} max-w-3xl animate-rise text-[2rem] font-extrabold leading-[1.08] tracking-[-0.02em] drop-shadow [animation-delay:.1s] sm:text-5xl">{{ $title }}</h1>
  @if($subtitle)<p class="mt-4 max-w-2xl animate-rise text-sm font-semibold leading-relaxed text-white/85 [animation-delay:.15s] sm:text-base">{{ $subtitle }}</p>@endif
  @if(trim($slot) !== '')<div class="mt-6 flex animate-rise flex-wrap items-center gap-3 [animation-delay:.2s]">{{ $slot }}</div>@endif
  @isset($after)<div class="mt-10 animate-rise [animation-delay:.25s]">{{ $after }}</div>@endisset
 </div>
</section>
