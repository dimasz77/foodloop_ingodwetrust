@extends('layout')
@section('title','Masuk')
@section('content')
<div class="mx-auto mt-10 max-w-md px-4"><form method="POST" action="{{ route('login') }}" class="card space-y-4">@csrf
 <h1 class="h2">Masuk ke FoodLoop</h1>
 <div><label class="lbl" for="e">Email</label><input id="e" name="email" type="email" required autofocus class="inp" value="{{ old('email') }}"></div>
 <div><label class="lbl" for="p">Kata sandi</label><input id="p" name="password" type="password" required class="inp"></div>
 <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="remember"> Ingat saya</label>
 <button class="btn w-full">Masuk</button>
 <p class="text-center text-xs text-slate-500">Belum punya akun? <a class="font-bold text-moss underline" href="{{ route('register') }}">Daftar</a></p>
 <div class="rounded-lg bg-amber-50 p-3 text-[11px] text-amber-900"><b>Akun demo</b> (kata sandi: <code>password</code>)<br>Pemberi: smiley@foodloop.test<br>Penerima: relawan@foodloop.test</div>
</form></div>
@endsection
