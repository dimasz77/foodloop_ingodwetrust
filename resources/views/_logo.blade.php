{{-- Logo FoodLoop: cincin "loop" terbuka dengan daun yang tumbuh dari ujungnya. Pakai: @include('_logo', ['light' => false, 'size' => 32]) --}}
@php $light = $light ?? false; $size = $size ?? 32; @endphp
<span class="inline-flex items-center gap-2.5">
 <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" fill="none" aria-hidden="true" class="shrink-0">
  <rect width="32" height="32" rx="9" fill="{{ $light ? '#ffffff' : '#1f6b3a' }}"/>
  <path d="M11.4 9.6A8.2 8.2 0 1 0 20.6 9.6" stroke="{{ $light ? '#1f6b3a' : '#ffffff' }}" stroke-width="3" stroke-linecap="round"/>
  <path d="M20.6 9.6C20.4 6.3 22.9 4.2 26.6 4.4C26.8 7.9 24.4 10.2 20.6 9.6Z" fill="#a3e635"/>
  <path d="M20.6 9.6L24 6.6" stroke="{{ $light ? '#1f6b3a' : '#1f6b3a' }}" stroke-width="1" stroke-linecap="round" opacity=".55"/>
  <circle cx="16" cy="17.6" r="2.4" fill="{{ $light ? '#1f6b3a' : '#a3e635' }}"/>
 </svg>
 <span class="text-[1.15rem] leading-none tracking-tight {{ $light ? 'text-white' : 'text-slate-900' }}"><span class="font-medium">food</span><span class="font-extrabold {{ $light ? 'text-lime-300' : 'text-moss' }}">loop</span></span>
</span>
