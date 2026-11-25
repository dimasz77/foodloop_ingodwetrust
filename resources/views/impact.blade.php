@extends('layout')
@section('title','Dampak')
@section('content')
@php
  $off = 0;
  $series = [['layak', 'Layak Konsumsi', '#1f6b3a'], ['organik', 'Organik', '#f28c28'], ['urgent', 'Segera Ambil', '#d9363e']];
  $maxMonth = max(1, max(array_map(fn ($m) => $m['layak'] + $m['organik'] + $m['urgent'], $monthly)));
  $maxBoard = max(1, $board->max('kg') ?? 1);
  $nf = fn ($v, $d = 0) => number_format($v, $d, ',', '.');
  $icon = fn (string $paths) => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths.'</svg>';
@endphp

{{-- PITA 1 (gelap): judul + angka utama --}}
<x-page-hero image="dampak.jpg" eyebrow="Dampak Komunitas" title="Dampak Nyata FoodLoop" subtitle="Setiap makanan yang diselamatkan berarti lebih banyak porsi untuk sesama, lebih sedikit sampah, dan emisi yang dicegah." :crumbs="['Dampak' => null]">
 <span class="chip bg-white/15 px-3 py-1 text-white ring-1 ring-white/20">Periode: sepanjang waktu</span><span class="chip bg-white/15 px-3 py-1 text-white ring-1 ring-white/20">Angka adalah estimasi</span>
 <x-slot:after>
  <dl class="grid divide-y divide-white/10 overflow-hidden rounded-2xl bg-white/[.06] ring-1 ring-white/10 backdrop-blur sm:grid-cols-3 sm:divide-x sm:divide-y-0">
   @foreach([
    [$t['kg'], 0, 'kg', 'Makanan diselamatkan', '<path d="M4 11h16a8 8 0 0 1-16 0z"/><path d="M12 11V7m0 0c0-2 1.5-3.5 4-3.5 0 2.2-1.6 3.5-4 3.5z"/>'],
    [$t['meals'], 0, 'porsi', 'Porsi tersalurkan', '<path d="M3 13h18M5 13a7 7 0 0 1 14 0"/><path d="M12 6V4M2 17h20"/>'],
    [$t['co2_ton'], 2, 'ton', 'CO₂e dicegah', '<path d="M7 18a4.5 4.5 0 1 1 .6-8.96A6 6 0 0 1 19 11a3.5 3.5 0 0 1-.5 7z"/><path d="M9 14l2 2 4-4"/>'],
   ] as [$v, $d, $s, $l, $p])
    <div class="flex items-center gap-4 p-6">
     <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-lime-300 text-[#0c1f19]">{!! $icon($p) !!}</span>
     <div><dt class="text-[0.75rem] font-bold uppercase tracking-wider text-white/60">{{ $l }}</dt>
      <dd class="text-4xl font-extrabold leading-tight"><span data-count="{{ $v }}" data-decimals="{{ $d }}">{{ $nf($v, $d) }}</span> <span class="text-base text-lime-300">{{ $s }}</span></dd>
      <dd class="mt-1"><a href="{{ route('about') }}" class="rounded bg-white/10 px-1.5 py-0.5 text-[0.6875rem] font-bold text-white/80 hover:bg-white/20">{{ ['Target SDG 12.3', 'Target SDG 2.1', 'Terkait SDG 13'][$loop->index] }}</a></dd></div>
    </div>
   @endforeach
  </dl>
  <p class="mt-3 text-[0.75rem] text-white/55">Dari {{ $t['reports'] }} laporan · {{ $nf($pending, 1) }} kg masih dalam proses penyaluran.</p>
 </x-slot:after>
</x-page-hero>

{{-- PITA 2 (lime): padanan agar angka mudah dibayangkan --}}
<section class="bg-lime-300 text-[#0c1f19]" aria-labelledby="eq-title">
 <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-12 md:grid-cols-[1fr_3fr]">
  <div data-reveal><p class="text-[0.75rem] font-extrabold uppercase tracking-[.2em]">Agar mudah dibayangkan</p><h2 id="eq-title" class="mt-2 text-3xl font-extrabold leading-tight">Setara dengan…</h2><p class="mt-2 text-[0.75rem] font-semibold text-[#0c1f19]/70">Estimasi: 0,12 kg CO₂e/km mobil · 22 kg CO₂/pohon/tahun.</p></div>
  <div class="grid gap-6 sm:grid-cols-3">
   @foreach([
    [$eq['car_km'], 0, 'km perjalanan mobil yang tidak terjadi', '<path d="M5 16h14M6 16l1.5-5h9L18 16M7 19a1 1 0 1 0 0-2M17 19a1 1 0 1 0 0-2"/><path d="M4 16v-2l2-3"/>'],
    [$eq['trees'], 1, 'pohon menyerap CO₂ selama setahun', '<path d="M12 21v-5M12 3l6 8h-3l4 5H5l4-5H6z"/>'],
    [$eq['meals'], 0, 'piring makan dari total makanan terselamatkan', '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/>'],
   ] as [$v, $d, $l, $p])
    <div class="border-l-[3px] border-[#0c1f19] pl-4" data-reveal style="transition-delay:{{ $loop->index * 100 }}ms">
     <span class="text-[#0c1f19]/80">{!! $icon($p) !!}</span>
     <b class="mt-1 block text-4xl font-extrabold" data-count="{{ $v }}" data-decimals="{{ $d }}">{{ $nf($v, $d) }}</b>
     <span class="text-xs font-semibold">{{ $l }}</span>
    </div>
   @endforeach
  </div>
 </div>
</section>

{{-- PITA 3 (putih): grafik bulanan + donat kategori --}}
<section class="border-b border-slate-200 bg-white">
 <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 lg:grid-cols-[2fr_1fr]">
  <div x-data="{h:null}" data-reveal>
   <p class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Tren</p>
   <div class="mt-2 flex flex-wrap items-end justify-between gap-3"><div><h2 class="text-xl font-extrabold">Makanan Tersalurkan per Bulan</h2><p class="text-xs text-slate-500">kg per kategori, 8 bulan terakhir · arahkan kursor atau tab ke batang untuk rincian</p></div>
    <ul class="flex flex-wrap gap-3 text-[0.75rem] text-slate-600" aria-label="Legenda">@foreach($series as [$k, $n, $c])<li class="flex items-center gap-1.5"><i class="h-2.5 w-2.5 rounded-sm" style="background:{{ $c }}"></i>{{ $n }}</li>@endforeach</ul></div>
   <div class="overflow-x-auto">
    <div class="relative mt-6 h-60 min-w-[480px]">
     <div class="flex h-full items-end gap-2 border-b border-slate-200">
      @foreach($monthly as $i => $m)
       @php $tot = $m['layak'] + $m['organik'] + $m['urgent']; @endphp
       <div class="relative flex h-full flex-1 flex-col items-center justify-end" tabindex="0" @mouseenter="h={{ $i }}" @mouseleave="h=null" @focus="h={{ $i }}" @blur="h=null" aria-label="{{ $m['m'] }}: {{ $nf($tot, 1) }} kg">
        <span class="mb-1 text-[0.6875rem] font-bold text-slate-600">{{ $tot > 0 ? $nf($tot) : '' }}</span>
        <div class="flex w-full max-w-[40px] flex-col-reverse gap-[2px] transition-opacity" :class="h!==null&&h!={{ $i }}&&'opacity-40'" style="height:{{ $tot / $maxMonth * 85 }}%">
         @foreach($series as [$k, $n, $c])@if($m[$k] > 0)<div class="w-full last:rounded-t" style="flex:{{ $m[$k] }};background:{{ $c }}"></div>@endif @endforeach
        </div>
        <div x-show="h=={{ $i }}" x-cloak x-transition.opacity class="pointer-events-none absolute bottom-full z-10 mb-1 w-40 rounded-lg bg-slate-900 p-2 text-[0.75rem] text-white shadow-xl">
         <b class="block border-b border-white/20 pb-1">{{ $m['m'] }} · {{ $nf($tot, 1) }} kg</b>
         @foreach($series as [$k, $n, $c])<div class="mt-1 flex items-center gap-2"><i class="h-0.5 w-3" style="background:{{ $c }}"></i><b>{{ $nf($m[$k], 1) }} kg</b><span class="text-white/60">{{ $n }}</span></div>@endforeach
        </div>
       </div>
      @endforeach
     </div>
    </div>
    <div class="flex min-w-[480px] gap-2 pt-1 text-center text-[0.6875rem] text-slate-500">@foreach($monthly as $m)<span class="flex-1">{{ $m['m'] }}</span>@endforeach</div>
   </div>
   <details class="mt-4 text-xs"><summary class="cursor-pointer font-bold text-moss">Lihat sebagai tabel</summary>
    <div class="mt-2 overflow-x-auto"><table class="w-full text-left"><thead class="text-slate-500"><tr><th class="py-1 pr-3">Bulan</th>@foreach($series as [$k, $n])<th class="py-1 pr-3 text-right">{{ $n }} (kg)</th>@endforeach</tr></thead><tbody>@foreach($monthly as $m)<tr class="border-t"><td class="py-1 pr-3">{{ $m['m'] }}</td>@foreach($series as [$k])<td class="py-1 pr-3 text-right">{{ $nf($m[$k], 1) }}</td>@endforeach</tr>@endforeach</tbody></table></div></details>
  </div>

  <div class="lg:border-l lg:border-slate-200 lg:pl-10" x-data="{h:null,cats:@js($cats)}" data-reveal>
   <p class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Komposisi</p>
   <h2 class="mt-2 text-xl font-extrabold">Dampak per Kategori</h2>
   <div class="relative mx-auto mt-6 w-fit">
    <svg viewBox="0 0 42 42" width="200" height="200" role="img" aria-label="Diagram donat dampak per kategori" style="transform:rotate(-90deg)">
     <circle r="15.9" cx="21" cy="21" fill="none" stroke="#e2e8f0" stroke-width="3.2"/>
     @foreach($cats as $i => [$n, $kg, $c])@php $l = $kg / $sum * 100; @endphp
      @if($l > 0)<circle r="15.9" cx="21" cy="21" fill="none" stroke="{{ $c }}" stroke-dasharray="{{ max(0, $l - 1.5) }} {{ 101.5 - $l }}" stroke-dashoffset="{{ -$off }}" stroke-linecap="round" class="cursor-pointer transition-all duration-300" :stroke-width="h=={{ $i }}?5:3.2" :opacity="h!==null&&h!={{ $i }}?.35:1" @mouseenter="h={{ $i }}" @mouseleave="h=null"/>@endif
      @php $off += $l; @endphp
     @endforeach
    </svg>
    <div class="pointer-events-none absolute inset-0 grid place-items-center text-center"><div><b class="text-xl" x-text="h===null?'{{ $nf(array_sum(array_column($cats, 1))) }} kg':Math.round(cats[h][1]).toLocaleString('id-ID')+' kg'">{{ $nf(array_sum(array_column($cats, 1))) }} kg</b><div class="text-[0.6875rem] text-slate-500" x-text="h===null?'Total tersalurkan':cats[h][0]">Total tersalurkan</div></div></div>
   </div>
   <ul class="mt-6 space-y-1 text-xs">@foreach($cats as $i => [$n, $kg, $c])<li class="flex items-center gap-3 rounded-xl p-2 transition" :class="h=={{ $i }}&&'bg-slate-50'" @mouseenter="h={{ $i }}" @mouseleave="h=null"><i class="h-3 w-3 shrink-0 rounded-full" style="background:{{ $c }}"></i><b class="flex-1">{{ $n }}</b><span class="text-slate-500">{{ $nf($kg) }} kg · {{ round($kg / $sum * 100) }}%</span></li>@endforeach</ul>
  </div>
 </div>
</section>

{{-- PITA 4 (gelap): mengapa penting --}}
<section class="relative isolate overflow-hidden bg-[#0c1f19] text-white">
 <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[#0c1f19] to-[#1f4b4a]"><x-contour /></div>
 <div class="mx-auto max-w-6xl px-4 py-16">
  <p class="sec !text-lime-300" data-reveal><span class="h-[3px] w-8 rounded bg-lime-300"></span>Mengapa FoodLoop penting</p>
  <div class="mt-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
   @foreach([['Kurangi Emisi', 'Mengurangi emisi metana dari sampah makanan yang dibuang ke TPA.'], ['Beri Makan Komunitas', 'Makanan layak konsumsi disalurkan kepada penerima yang membutuhkan.'], ['Cegah Pemborosan', 'Mencegah makanan layak berakhir di TPA dan mengurangi pemborosan pangan.'], ['Ekonomi Sirkular', 'Sisa organik diolah menjadi kompos atau pakan maggot BSF agar tetap bernilai.']] as [$a, $b])
    <div data-reveal style="transition-delay:{{ $loop->index * 90 }}ms"><span class="text-5xl font-extrabold text-lime-300/30">0{{ $loop->iteration }}</span><h3 class="mt-2 text-xl font-extrabold">{{ $a }}</h3><p class="mt-2 text-sm leading-relaxed text-white/70">{{ $b }}</p></div>
   @endforeach
  </div>
 </div>
</section>

{{-- PITA 5 (krem): papan peringkat --}}
<section class="bg-cream">
 <div class="mx-auto grid max-w-6xl gap-10 px-4 py-16 lg:grid-cols-[1fr_2fr]">
  <div data-reveal>
   <p class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Papan Peringkat</p>
   <h2 class="mt-2 text-3xl font-extrabold leading-tight">Pemberi dengan Kontribusi Terbesar</h2>
   <p class="mt-3 text-sm text-slate-600">Diurutkan berdasarkan total kg makanan yang tersalurkan. Setiap laporan surplus menambah dampak Anda.</p>
   <div class="mt-6 flex flex-wrap gap-3">
    @if(auth()->check() && !auth()->user()->isProvider())<a class="btn btn-g px-5 py-3" href="{{ route('map') }}">Cari Makanan</a>@else<a class="btn btn-g px-5 py-3" href="{{ route('report.create') }}">Laporkan Surplus</a>@endif
    @auth<a href="{{ route('history') }}" class="btn btn-w px-5 py-3">Lencana Saya</a>@endauth
   </div>
  </div>
  <ol class="divide-y divide-slate-200 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5" aria-label="Papan peringkat">
   @forelse($board as $i => $b)
    <li class="relative" data-reveal style="transition-delay:{{ $i * 70 }}ms">
     <div class="absolute inset-y-0 left-0 bg-moss/[.07]" style="width:{{ $b['kg'] / $maxBoard * 100 }}%" aria-hidden="true"></div>
     <div class="relative flex items-center gap-4 px-5 py-4 text-xs"><span class="grid h-10 w-10 shrink-0 place-items-center rounded-full text-sm font-extrabold {{ ['bg-amber-300 text-amber-950', 'bg-slate-300 text-slate-800', 'bg-orange-300 text-orange-950'][$i] ?? 'bg-slate-100 text-slate-500' }}" aria-label="Peringkat {{ $i + 1 }}">{{ $i + 1 }}</span>
      <span class="min-w-0 flex-1"><b class="block truncate text-sm">{{ $b['name'] }}</b><span class="text-slate-500">{{ $b['trips'] }} penyaluran</span></span>
      <span class="text-right"><b class="text-sm">{{ $nf($b['kg']) }} kg</b><br><span class="text-moss">{{ $nf($b['co2'], 1) }} kg CO₂e</span></span></div>
    </li>
   @empty<li class="p-6 text-xs text-slate-500">Belum ada data penyaluran.</li>@endforelse
  </ol>
 </div>
</section>
@endsection
