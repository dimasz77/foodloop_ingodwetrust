{{-- Kerangka halaman akun (Masuk, Daftar, Profil): latar terang, judul di kiri atas, konten terpusat. --}}
@props(['title', 'subtitle' => null])
<section class="min-h-[calc(100svh-4.5rem)] bg-cream">
 <div class="mx-auto max-w-4xl px-4 pb-20 pt-12 sm:pt-16">
  <h1 class="animate-rise text-3xl font-extrabold tracking-tight text-[#173a39] sm:text-4xl">{{ $title }}</h1>
  @if($subtitle)<p class="mt-3 animate-rise text-sm text-slate-700 [animation-delay:.05s] sm:text-base">{{ $subtitle }}</p>@endif
  <div class="mt-10 animate-rise [animation-delay:.1s]">{{ $slot }}</div>
 </div>
</section>
