{{-- Bantuan kontekstual yang bisa dibuka-tutup (Heuristik #10: bantuan & dokumentasi). --}}
@props(['title' => 'Butuh bantuan di halaman ini?'])
<details {{ $attributes->merge(['class' => 'group rounded-2xl bg-white text-xs shadow-sm ring-1 ring-black/5 open:ring-moss/30']) }}>
 <summary class="flex cursor-pointer list-none items-center gap-3 p-4 font-bold text-slate-700 [&::-webkit-details-marker]:hidden">
  <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-moss/10 font-extrabold text-moss">?</span>
  <span class="flex-1">{{ $title }}</span>
  <svg class="h-4 w-4 text-slate-400 transition group-open:rotate-180" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6l4 4 4-4"/></svg>
 </summary>
 <div class="space-y-2 px-4 pb-4 pl-14 leading-relaxed text-slate-600">{{ $slot }}</div>
</details>
