@extends('layout')
@section('title','Masuk')
@section('content')
@php $demo = str_ends_with((string) request('demo'), '@foodloop.test') ? request('demo') : null; @endphp
<x-page-hero eyebrow="Masuk" title="Selamat Datang Kembali" subtitle="Masuk untuk melaporkan surplus, mengklaim makanan, dan melihat lencana Anda." :crumbs="['Masuk' => null]" compact />
<div class="pull-up mx-4 grid max-w-4xl md:mx-auto overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-black/5 md:grid-cols-2" x-data="{caps:false,email:@js(old('email', $demo ?? '')),pw:@js($demo ? 'password' : ''),show:false,busy:false}">
 <div class="relative hidden overflow-hidden bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] p-8 text-white md:block">
  <x-contour />
  <div class="relative">@include('_logo', ['light' => true, 'size' => 36])<h2 class="mt-6 text-2xl font-extrabold leading-tight">Tiga peran,<br><span class="text-lime-300">satu siklus.</span></h2><p class="mt-2 text-xs text-white/80">Peran ditentukan saat mendaftar dan menentukan menu yang Anda lihat.</p>
   <ul class="mt-6 space-y-3 text-xs">@foreach(['Pemberi: laporkan surplus dalam 1 menit', 'Penerima: klaim makanan layak di peta', 'Pengolah organik: olah sisa jadi maggot/kompos'] as $l)<li class="rounded-xl bg-white/10 p-3">{{ $l }}</li>@endforeach</ul></div>
 </div>
 <form method="POST" action="{{ route('login') }}" class="space-y-4 p-6 sm:p-8" @submit="busy=true">@csrf
  <h1 class="h2">Masuk ke FoodLoop</h1>
  @if($demo)<p class="rounded-lg bg-lime-100 p-2 text-xs font-semibold text-[#0c1f19]" role="status">Akun demo sudah terisi. Tekan <b>Masuk</b> untuk melanjutkan.</p>@endif
  <div><label class="lbl" for="e">Email</label><input id="e" name="email" type="email" required autofocus class="inp" @error('email') aria-invalid="true" aria-describedby="e-err" @enderror x-model="email" autocomplete="email">@error('email')<p id="e-err" class="err">⚠ {{ $message }}</p>@enderror</div>
  <div><label class="lbl" for="p">Kata sandi</label><div class="relative"><input id="p" name="password" :type="show?'text':'password'" required class="inp pr-16" x-model="pw" autocomplete="current-password" @keyup="caps=$event.getModifierState && $event.getModifierState('CapsLock')"><button type="button" class="absolute inset-y-0 right-2 text-[0.75rem] font-bold text-slate-500 hover:text-moss" @click="show=!show" x-text="show?'Sembunyi':'Lihat'"></button></div><p x-show="caps" x-cloak class="hint !text-amber-700">⚠ Caps Lock aktif</p></div>
  <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="remember" class="accent-moss"> Ingat saya</label>
  <button class="btn w-full py-3 text-sm" :disabled="busy" x-text="busy?'⏳ Memproses…':'Masuk'">Masuk</button>
  <p class="text-center text-xs text-slate-500">Belum punya akun? <a class="font-bold text-moss underline" href="{{ route('register') }}">Daftar</a></p>
  <div class="rounded-xl bg-amber-50 p-3 text-[0.75rem] text-amber-900"><b>Coba akun demo</b> (kata sandi: <code>password</code>)
   <div class="mt-2 grid gap-2 sm:grid-cols-3">@foreach([['smiley@foodloop.test', 'Pemberi'], ['relawan@foodloop.test', 'Penerima'], ['bsf@foodloop.test', 'Pengolah Organik']] as [$em, $l])<button type="button" class="rounded-lg bg-white px-2 py-2 font-bold shadow-sm ring-1 ring-amber-200 transition hover:ring-amber-400" @click="email='{{ $em }}';pw='password'">{{ $l }}<span class="block truncate text-[0.6875rem] font-normal text-slate-500">{{ $em }}</span></button>@endforeach</div></div>
 </form>
</div>
@endsection
