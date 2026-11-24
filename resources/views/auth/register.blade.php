@extends('layout')
@section('title','Daftar')
@section('content')
<div class="mx-auto mt-10 max-w-lg px-4"><form method="POST" action="{{ route('register') }}" class="card space-y-4" x-data="{role:'{{ old('role',$role) }}'}">@csrf
 <h1 class="h2">Daftar sebagai</h1>
 <div class="grid grid-cols-2 gap-3" role="radiogroup" aria-label="Pilih peran">
  @foreach(['provider'=>['🍽️','Pemberi Makanan','Restoran, hotel, katering, event/kampus yang memiliki surplus'],'recipient'=>['🤝','Penerima Makanan','Relawan, panti, dapur umum, atau pengolah organik']] as $v=>[$i,$t,$d])
  <label class="cursor-pointer rounded-xl border-2 p-3 text-center text-xs" :class="role=='{{ $v }}'?'border-teal2 bg-green-50':'border-slate-200'"><input class="sr-only" type="radio" name="role" value="{{ $v }}" x-model="role"><div class="text-2xl">{{ $i }}</div><b>{{ $t }}</b><div class="text-[10px] text-slate-500">{{ $d }}</div></label>@endforeach</div>
 <div class="grid gap-3 sm:grid-cols-2"><div><label class="lbl" for="n">Nama lengkap</label><input id="n" name="name" required class="inp" value="{{ old('name') }}"></div>
  <div><label class="lbl" for="o" x-text="role=='provider'?'Nama usaha / instansi':'Nama organisasi / relawan'">Organisasi</label><input id="o" name="organization" required class="inp" value="{{ old('organization') }}"></div>
  <div><label class="lbl" for="ph">No WhatsApp</label><input id="ph" name="phone" class="inp" inputmode="tel" value="{{ old('phone') }}"></div>
  <div><label class="lbl" for="em">Email</label><input id="em" name="email" type="email" required class="inp" value="{{ old('email') }}"></div>
  <div><label class="lbl" for="pw">Kata sandi (min. 6)</label><input id="pw" name="password" type="password" required class="inp"></div>
  <div><label class="lbl" for="pc">Ulangi kata sandi</label><input id="pc" name="password_confirmation" type="password" required class="inp"></div></div>
 <button class="btn w-full">Buat Akun</button><p class="text-center text-xs text-slate-500">Sudah punya akun? <a class="font-bold text-moss underline" href="{{ route('login') }}">Masuk</a></p>
</form></div>
@endsection
