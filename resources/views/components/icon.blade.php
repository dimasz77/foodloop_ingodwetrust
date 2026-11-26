{{--
  Ikon FoodLoop. Ikon garis bawaan dipakai sampai ada file pengganti di
  public/images/ikon/{name}.svg | .png | .webp (file tersebut otomatis dipakai).
  Contoh: <x-icon name="kompos" class="h-7 w-7" />
--}}
@props(['name', 'label' => null])
@php
  $custom = null;
  foreach (['svg', 'png', 'webp'] as $ext) {
      if (file_exists(public_path("images/ikon/{$name}.{$ext}"))) {
          $custom = asset("images/ikon/{$name}.{$ext}");
          break;
      }
  }
  $paths = [
      'lapor' => '<rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 10h6M9 14h6M9 18h3"/>',
      'klaim' => '<path d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0C18.5 15.4 12 21 12 21z"/><path d="M9.5 10l2 2 3.5-3.5"/>',
      'jemput' => '<path d="M2.5 6h11v10h-11zM13.5 9.5h4l3 3.5V16h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/>',
      'salur' => '<path d="M12 9.5c-1.2-2.2-5-2-5 1 0 2.2 2.6 3.8 5 5.5 2.4-1.7 5-3.3 5-5.5 0-3-3.8-3.2-5-1z"/><path d="M2.5 17.5c2 0 3-1.5 5.5-1.5h3M21.5 17.5c-2 0-3-1.5-5.5-1.5h-3"/>',
      'reroute' => '<path d="M4 12a8 8 0 0 1 13.7-5.6L20 8.5M20 4v4.5h-4.5M20 12a8 8 0 0 1-13.7 5.6L4 15.5M4 20v-4.5h4.5"/>',
      'maggot' => '<path d="M5 17c0-3 3-3 3-6s3-4 5-2 0 4 2 5 4 0 4 2-2 3-4 3H8c-2 0-3-1-3-2z"/><circle cx="16.5" cy="17" r=".6" fill="currentColor"/>',
      'kompos' => '<path d="M12 20v-7M12 13c0-3.5-2.5-6-6-6 0 3.5 2.5 6 6 6zM12 13c0-3 2.2-5.5 5.5-5.5 0 3-2.2 5.5-5.5 5.5z"/><path d="M5 20h14"/>',
      'peta' => '<path d="M3 6l6-2 6 2 6-2v14l-6 2-6-2-6 2z"/><path d="M9 4v14M15 6v14"/>',
      'filter' => '<path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0"/><circle cx="16" cy="6" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="18" cy="18" r="2"/>',
      'whatsapp' => '<path d="M4 20l1.3-4A8 8 0 1 1 8.5 19z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-1.8-1.8l.8-1-1-2z"/>',
      'bukti' => '<path d="M4 8h3l1.5-2.5h7L17 8h3v11H4z"/><circle cx="12" cy="13" r="3.5"/>',
      'baru' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 8v8M8 12h8"/>',
      'diklaim' => '<path d="M8 13V5.5a1.5 1.5 0 0 1 3 0V11M11 10V4.5a1.5 1.5 0 0 1 3 0V11M14 10.5V6a1.5 1.5 0 0 1 3 0v7c0 4-2.5 7-6 7-2.5 0-4-1.2-5.5-3.5L4 13.5a1.5 1.5 0 0 1 2.5-1.6L8 14"/>',
      'tersalurkan' => '<circle cx="12" cy="12" r="8.5"/><path d="M8 12.5l2.7 2.7L16.5 9.5"/>',
      'porsi' => '<path d="M3 13h18M5 13a7 7 0 0 1 14 0"/><path d="M12 6V4M2 17h20"/>',
      'co2' => '<path d="M7 18a4.5 4.5 0 1 1 .6-8.96A6 6 0 0 1 19 11a3.5 3.5 0 0 1-.5 7z"/><path d="M9 14l2 2 4-4"/>',
      'mobil' => '<path d="M5 16h14M6 16l1.5-5h9L18 16"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/><path d="M4 16v-2l2-3"/>',
      'pohon' => '<path d="M12 21v-5M12 3l6 8h-3l4 5H5l4-5H6z"/>',
      'hitung' => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 7h8M8 11h2M12 11h0M16 11h0M8 15h2M12 15h0M16 15v3M8 18h2"/>',
  ];
@endphp
@if($custom)
 <img src="{{ $custom }}" alt="{{ $label ?? '' }}" {{ $attributes->merge(['class' => 'h-7 w-7 object-contain']) }} loading="lazy">
@else
 <svg {{ $attributes->merge(['class' => 'h-7 w-7']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif>{!! $paths[$name] ?? $paths['baru'] !!}</svg>
@endif
