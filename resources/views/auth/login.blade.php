@extends('layout')
@section('title','Masuk')
@section('content')
@php $demo = str_ends_with((string) request('demo'), '@foodloop.test') ? request('demo') : null; @endphp
<x-account-shell title="Masuk" subtitle="Masuk untuk melaporkan surplus, mengklaim makanan, dan melihat dampak yang telah Anda berikan.">
 <form method="POST" action="{{ route('login') }}" class="mx-auto max-w-3xl" @submit="busy=true"
  x-data="{caps:false,email:@js(old('email', $demo ?? '')),pw:@js($demo ? 'password' : ''),show:false,busy:false}">@csrf

  <div class="flex justify-center">
   <div class="grid h-40 w-40 place-items-center rounded-full bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] shadow-lg ring-4 ring-white">
    <svg class="h-20 w-20" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M11.4 9.6A8.2 8.2 0 1 0 20.6 9.6" stroke="#fff" stroke-width="3" stroke-linecap="round"/><path d="M20.6 9.6C20.4 6.3 22.9 4.2 26.6 4.4C26.8 7.9 24.4 10.2 20.6 9.6Z" fill="#a3e635"/><circle cx="16" cy="17.6" r="2.4" fill="#a3e635"/></svg>
   </div>
  </div>

  @if($demo)<p class="mx-auto mt-6 max-w-md rounded-xl bg-lime-100 p-3 text-center text-xs font-semibold text-[#0c1f19]" role="status">Akun demo sudah terisi. Tekan <b>Masuk</b> untuk melanjutkan.</p>@endif

  <div class="mt-10 grid gap-x-10 gap-y-6 md:grid-cols-2">
   <div><label class="lbl text-base" for="e">Email</label>
    <input id="e" name="email" type="email" required autofocus class="inp rounded-xl py-3" x-model="email" autocomplete="email" placeholder="Masukkan email Anda" @error('email') aria-invalid="true" aria-describedby="e-err" @enderror>
    @error('email')<p id="e-err" class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="p">Kata sandi</label>
    <div class="relative"><input id="p" name="password" :type="show?'text':'password'" required class="inp rounded-xl py-3 pr-20" x-model="pw" autocomplete="current-password" placeholder="Masukkan kata sandi" @keyup="caps=$event.getModifierState && $event.getModifierState('CapsLock')">
     <button type="button" class="absolute inset-y-0 right-3 text-xs font-bold text-slate-500 hover:text-moss" @click="show=!show" :aria-pressed="show" x-text="show?'Sembunyikan':'Lihat'">Lihat</button></div>
    <p x-show="caps" x-cloak class="hint !text-amber-700">⚠ Caps Lock aktif</p></div>
  </div>

  <label class="mt-5 flex items-center gap-2 text-sm"><input type="checkbox" name="remember" class="h-4 w-4 accent-moss"> Ingat saya di perangkat ini</label>

  <div class="mt-10 flex flex-col items-center gap-4">
   <button class="btn w-full max-w-md bg-[#173a39] py-3.5 text-sm normal-case tracking-normal" :disabled="busy">
    <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.5 2.5h3v11h-3M2.5 8h7M6.5 5l3 3-3 3"/></svg>
    <span x-text="busy?'Memproses…':'Masuk'">Masuk</span></button>
   <p class="text-sm text-slate-600">Belum punya akun? <a class="font-bold text-moss underline underline-offset-2" href="{{ route('register') }}">Daftar sekarang</a></p>
  </div>

  <div class="mx-auto mt-10 max-w-2xl rounded-2xl bg-white p-4 text-xs text-slate-600 shadow-sm ring-1 ring-black/5">
   <p class="text-center"><b class="text-slate-800">Coba akun demo</b> · kata sandi <code class="rounded bg-slate-100 px-1.5 py-0.5 font-bold">password</code></p>
   <div class="mt-3 grid gap-2 sm:grid-cols-3">
    @foreach([['smiley@foodloop.test', 'Pemberi Makanan'], ['relawan@foodloop.test', 'Penerima Makanan'], ['bsf@foodloop.test', 'Pengolah Organik']] as [$em, $l])
     <button type="button" class="rounded-xl bg-cream px-3 py-2.5 text-left font-bold text-slate-800 ring-1 ring-black/5 transition hover:ring-moss" @click="email='{{ $em }}';pw='password'">{{ $l }}<span class="block truncate text-[0.6875rem] font-normal text-slate-500">{{ $em }}</span></button>
    @endforeach
   </div>
  </div>
 </form>
</x-account-shell>
@endsection
