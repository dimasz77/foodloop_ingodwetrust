{{-- Motif garis kontur dekoratif untuk latar gelap (dipakai saat foto belum tersedia). --}}
<svg {{ $attributes->merge(['class' => 'absolute inset-0 h-full w-full opacity-[.10]']) }} viewBox="0 0 800 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
 <g fill="none" stroke="#bef264" stroke-width="1.2">
  @foreach(range(1, 14) as $i)<ellipse cx="620" cy="260" rx="{{ $i * 38 }}" ry="{{ $i * 26 }}" transform="rotate(-18 620 260)"/>@endforeach
 </g>
</svg>
