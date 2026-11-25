@extends('layout')
@section('title','Status Laporan')
@section('content')
@php
  $isProvider = auth()->user()->role == 'provider';
  $page = $isProvider ? 'Status Laporan' : 'Klaim Saya';
  if ($cur) {
      $r = $cur; $d = $r->status == 'delivered'; $step = $r->step;
      $km = $r->partner ? \App\Models\Partner::km($r->lat, $r->lng, $r->partner->lat, $r->partner->lng) : null;
      $steps = [['📝', 'Menunggu Klaim', 'Laporan tampil di peta'], ['🙋', 'Diklaim', 'Penerima menyiapkan penjemputan'], ['🚚', 'Dalam Perjalanan', 'Makanan sedang diantar'], ['✅', 'Tersalurkan', 'Bukti serah terima diunggah']];
      $canAct = !$isProvider && $r->recipient_id == auth()->id();
      $heroTitle = $d ? 'Penyaluran Selesai' : ($isProvider ? 'Laporan Sedang Diproses' : 'Klaim Anda Sedang Berjalan');
  }
@endphp

@if(!$cur)
 <x-page-hero :eyebrow="$page" :title="$isProvider ? 'Belum Ada Laporan' : 'Belum Ada Klaim'" :subtitle="$isProvider ? 'Laporkan surplus pertama Anda dan pantau perjalanannya di sini.' : 'Temukan makanan di sekitar Anda dan klaim untuk disalurkan.'" :crumbs="[$page => null]">
  @if($isProvider)<a class="btn btn-lime px-5 py-3 text-sm" href="{{ route('report.create') }}">Buat Laporan</a>@else<a class="btn btn-lime px-5 py-3 text-sm" href="{{ route('map') }}">Buka Peta</a>@endif
 </x-page-hero>
 <div class="pull-up mx-auto max-w-6xl px-4">
  <div class="card mx-auto max-w-lg py-10 text-center shadow-xl" data-reveal><div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-moss/10 text-moss"><svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16v13H4z"/><path d="M4 7l2-3h12l2 3M9 11h6"/></svg></div>
   <p class="mt-3 text-sm font-bold text-slate-800">Halaman ini masih kosong</p><p class="mt-1 text-xs text-slate-500">{{ $isProvider ? 'Setelah Anda mengirim laporan, status, penerima, dan bukti penyaluran akan tampil di sini.' : 'Setelah Anda mengklaim makanan, kontak pemberi dan langkah berikutnya akan tampil di sini.' }}</p></div>
 </div>
@else
 <x-page-hero :eyebrow="$page" :title="$heroTitle" :subtitle="$r->title.' · '.$r->provider_name" :crumbs="[$page => null]" compact>
  <span class="chip {{ $r->category == 'layak' ? 'bg-lime-300 text-[#0c1f19]' : 'bg-amber-400 text-[#0c1f19]' }} px-3 py-1">{{ $r->category == 'layak' ? 'Human-First' : 'Earth-First' }}</span>
  <span class="chip bg-white/15 px-3 py-1 text-white ring-1 ring-white/20">{{ $r->status_label }}</span>
  @if($r->urgent_now)<span class="chip bg-red-600 px-3 py-1 text-white">Darurat</span>@endif
  @if($r->rerouted)<span class="chip bg-white/15 px-3 py-1 text-white ring-1 ring-white/20">Dialihkan otomatis</span>@endif
 </x-page-hero>
<div class="pull-up mx-auto max-w-6xl px-4">

{{-- Pemilih laporan (pengenalan, bukan ingatan) --}}
@if($others->count() > 1)
 <nav class="mb-5 flex gap-2 overflow-x-auto rounded-2xl bg-white p-2 text-[0.75rem] shadow-xl ring-1 ring-black/5" aria-label="Laporan lainnya"><span class="flex shrink-0 items-center px-2 font-extrabold uppercase tracking-wide text-slate-400">Laporan:</span>@foreach($others as $o)<a href="{{ route('status', ['id' => $o->id]) }}" @if($o->id == $r->id) aria-current="page" @endif class="flex shrink-0 items-center gap-1.5 rounded-full border px-3 py-1.5 font-semibold transition {{ $o->id == $r->id ? 'border-[#0c1f19] bg-[#0c1f19] text-white' : 'bg-white hover:border-moss' }}"><span class="h-2 w-2 rounded-full" style="background:{{ $o->status == 'delivered' ? '#94a3b8' : $o->color }}"></span>{{ \Illuminate\Support\Str::limit($o->title, 24) }}</a>@endforeach</nav>
@endif

<div class="grid gap-6 rounded-2xl bg-white p-5 shadow-xl ring-1 ring-black/5 md:grid-cols-[1.4fr_1fr]">
 <div>
  <p class="sec"><span class="h-[3px] w-8 rounded bg-moss"></span>Kontribusi ini</p>
  <div class="mt-4 grid grid-cols-3 gap-3 text-center">
   @foreach([[$r->portions, 0, 'Porsi'], [$r->weight_kg, 1, 'kg'], [$r->co2e, 1, 'kg CO₂e dicegah']] as [$v, $dc, $l])
    <div class="rounded-2xl bg-cream p-4"><b class="text-2xl text-moss" data-count="{{ $v }}" data-decimals="{{ $dc }}">{{ number_format($v, $dc, ',', '.') }}</b><div class="text-[0.6875rem] font-semibold text-slate-500">{{ $l }}</div></div>
   @endforeach
  </div>
 </div>
 @if($r->photo_url)<img src="{{ $r->photo_url }}" alt="Foto {{ $r->title }}" class="h-44 w-full rounded-xl object-cover">
 @else<div class="grid h-44 place-items-center rounded-xl bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] text-white/70" role="img" aria-label="Tidak ada foto"><span class="text-center text-xs"><svg class="mx-auto mb-1 h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 8"/></svg>Tidak ada foto makanan</span></div>@endif
</div>

{{-- Timeline --}}
<section class="card mt-8" data-reveal>
 <h2 class="text-sm font-extrabold">Perjalanan Makanan</h2>
 <ol class="relative mt-6 grid grid-cols-4 text-center text-[0.75rem]" aria-label="Tahap status">
  <span class="absolute left-[12.5%] right-[12.5%] top-5 h-1 rounded bg-slate-200" aria-hidden="true"></span>
  <span class="absolute left-[12.5%] top-5 h-1 rounded bg-moss transition-all duration-1000" style="width:{{ $step * 25 }}%" aria-hidden="true"></span>
  @foreach($steps as $i => [$ic, $s, $desc])
   <li class="relative" @if($i == $step) aria-current="step" @endif>
    <span class="relative mx-auto grid h-11 w-11 place-items-center rounded-full text-lg ring-4 ring-white transition {{ $i < $step || ($i == $step && $d) ? 'bg-moss text-white' : ($i == $step ? 'bg-white ring-moss/30 shadow-lg' : 'bg-slate-100 grayscale') }}">@if($i == $step && !$d)<span class="absolute inset-0 animate-ring rounded-full bg-moss/40"></span>@endif<span class="relative">{{ $ic }}</span></span>
    <b class="mt-2 block {{ $i <= $step ? 'text-moss' : 'text-slate-400' }}">{{ $s }}</b><span class="hidden text-[0.6875rem] text-slate-500 sm:block">{{ $desc }}</span></li>
  @endforeach
 </ol>
 @if(!$d)
  <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-xl {{ $r->urgent_now ? 'bg-red-50' : 'bg-slate-50' }} p-4">
   <div class="text-xs"><b>⏱ Sisa waktu</b><br><span class="text-slate-500">{{ $r->status == 'waiting' && $r->category == 'layak' ? 'Jika tidak diklaim tepat waktu, laporan otomatis dialihkan ke jalur organik.' : 'Batas waktu penjemputan.' }}</span></div>
   <span class="rounded-lg bg-white px-3 py-2 font-mono text-xl font-extrabold shadow-sm {{ $r->urgent_now ? 'text-red-700' : '' }}" data-exp="{{ $r->expires_at->toIso8601String() }}">--:--:--</span>
  </div>
 @endif
</section>

<div class="mt-6 grid gap-6 md:grid-cols-2">
 {{-- Mitra tujuan --}}
 <section class="card text-center" data-reveal>
  <div class="grid h-32 place-items-center rounded-xl bg-gradient-to-br from-green-100 to-lime-50 text-6xl" role="img" aria-label="Mitra tujuan">{{ $r->category == 'layak' ? '🏡' : '🌱' }}</div>
  <p class="mt-3 text-[0.75rem] text-slate-500">Mitra tujuan terdekat</p><p class="text-sm font-extrabold">{{ $r->partner?->name ?? '-' }}</p>
  <p class="text-[0.75rem] text-slate-500">{{ $km !== null ? ($km < 1 ? round($km * 1000).' m' : number_format($km, 1, ',', '.').' km').' dari lokasi penjemputan' : '' }}</p>
  <a class="btn btn-w mt-3" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ $r->lat }},{{ $r->lng }}">📍 Lokasi penjemputan ↗</a>
 </section>

 {{-- Penerima / pemberi --}}
 <section class="card text-center" data-reveal>
  @if($r->volunteer_name)
   <p class="text-xs text-slate-500">{{ $isProvider ? ($d ? 'Disalurkan oleh' : 'Sedang diambil oleh') : 'Pemberi makanan' }}</p>
   <div class="mx-auto mt-2 grid h-16 w-16 place-items-center rounded-full bg-slate-100 text-3xl">{{ $isProvider ? '🧑🏽' : '🏪' }}</div>
   <p class="mt-2 text-sm font-extrabold">{{ $isProvider ? $r->volunteer_name : $r->provider_name }} <span class="text-moss">●</span></p>
   @if($contactUrl)<a class="btn mt-3 bg-[#25D366] hover:bg-[#1da851]" target="_blank" rel="noopener" href="{{ $contactUrl }}">💬 Hubungi via WhatsApp</a>
   @else<p class="mt-3 text-[0.75rem] text-slate-400">Nomor WhatsApp belum tersedia.</p>@endif
  @else
   <div class="mx-auto grid h-16 w-16 animate-pulse place-items-center rounded-full bg-slate-100 text-3xl">🔎</div>
   <p class="mt-2 text-sm font-extrabold">Mencari penerima…</p><p class="text-[0.75rem] text-slate-500">Laporan Anda tampil di peta. Anda akan melihat nama penerima di sini setelah diklaim.</p>
   <a class="btn btn-w mt-3" href="{{ route('map', ['focus' => $r->id]) }}">Lihat di peta</a>
  @endif
 </section>
</div>

@if($d)
 <section class="card mt-6 grid items-center gap-5 md:grid-cols-[1fr_1.4fr]" data-reveal>
  @if($r->proof_path)<a href="{{ asset('storage/'.$r->proof_path) }}" target="_blank" class="block overflow-hidden rounded-xl"><img src="{{ asset('storage/'.$r->proof_path) }}" alt="Bukti penyaluran" class="h-48 w-full object-cover transition hover:scale-105"></a>@endif
  <div><h2 class="text-sm font-extrabold">💌 Pesan dari Penerima</h2>
   @if($r->thank_note)<blockquote class="mt-2 rounded-xl bg-green-50 p-4 text-sm italic text-slate-700">“{{ $r->thank_note }}”</blockquote><p class="mt-1 text-right text-[0.75rem] text-slate-500">— {{ $r->volunteer_name }}, {{ $r->delivered_at?->locale('id')->translatedFormat('d F Y H:i') }}</p>
   @else<p class="mt-2 text-xs text-slate-500">Tersalurkan pada {{ $r->delivered_at?->locale('id')->translatedFormat('d F Y H:i') }}. Terima kasih atas kontribusinya!</p>@endif
   <a class="btn btn-w mt-3" href="{{ route('history') }}">🏅 Lihat lencana saya</a></div>
 </section>
@endif

{{-- Aksi --}}
@if(!$d && ($canAct || ($isProvider && $r->status == 'waiting' && $r->category == 'layak')))
<section class="card mt-6" data-reveal><h2 class="text-sm font-extrabold">Aksi</h2>
 <div class="mt-3 flex flex-wrap items-start gap-3">
  @if($isProvider && $r->status == 'waiting' && $r->category == 'layak')<form method="POST" action="{{ route('report.act', [$r, 'reroute']) }}">@csrf<button class="btn btn-w">🔁 Simulasi Auto Re-route</button></form>@endif
  @if($canAct && $r->status == 'claimed')<form method="POST" action="{{ route('report.act', [$r, 'start']) }}">@csrf<button class="btn">🚚 Mulai Antar</button></form>@endif
 </div>
 @if($canAct && in_array($r->status, ['claimed', 'on_the_way']))
  <form method="POST" action="{{ route('report.act', [$r, 'complete']) }}" enctype="multipart/form-data" class="mt-4 grid gap-3 rounded-xl border-2 border-dashed border-slate-200 p-4 sm:grid-cols-2" x-data="{src:null,busy:false}" @submit="busy=true">@csrf
   <label class="grid h-40 cursor-pointer place-items-center overflow-hidden rounded-xl bg-slate-50 text-center text-xs text-slate-500 hover:bg-slate-100"><template x-if="src"><img :src="src" alt="Pratinjau bukti" class="h-full w-full object-cover"></template><span x-show="!src">📷<br><b>Pilih foto bukti serah terima</b></span>
    <input type="file" name="proof" accept="image/*" required class="sr-only" @change="src=$event.target.files[0]?URL.createObjectURL($event.target.files[0]):null"></label>
   <div><label class="lbl" for="note">Ucapan terima kasih untuk pemberi (opsional)</label><textarea id="note" name="note" maxlength="200" rows="3" class="inp" placeholder="Terima kasih, makanannya sudah sampai!">{{ old('note') }}</textarea>
    <button class="btn btn-g mt-2 w-full" :disabled="busy" x-text="busy?'⏳ Mengunggah…':'✅ Selesaikan Penyaluran'">✅ Selesaikan Penyaluran</button></div>
  </form>
  <form method="POST" action="{{ route('report.act', [$r, 'release']) }}" class="mt-3 text-right" x-data="{sure:false}" @submit="if(!sure){$event.preventDefault();sure=true}">@csrf<button class="text-[0.75rem] font-bold text-red-600 hover:underline" x-text="sure?'Tekan sekali lagi untuk membatalkan klaim':'Batalkan klaim'">Batalkan klaim</button></form>
 @endif
</section>
@endif
<x-help class="mt-6" title="Apa arti setiap status?">
 <p><b>Menunggu Klaim</b>: laporan tampil di peta. Jika makanan layak tidak diklaim sampai batas waktu, sistem otomatis mengalihkannya ke jalur organik.</p>
 <p><b>Diklaim</b>: penerima sudah mengambil tugas ini. Gunakan tombol WhatsApp untuk berkoordinasi.</p>
 <p><b>Dalam Perjalanan</b>: makanan sedang dijemput/diantar. Penerima dapat <b>membatalkan klaim</b> bila berhalangan, sehingga makanan kembali tersedia.</p>
 <p><b>Tersalurkan</b>: foto bukti serah terima sudah diunggah dan dampaknya tercatat di lencana Anda.</p>
</x-help>
</div>
@endif
@endsection
