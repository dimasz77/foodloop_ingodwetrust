@extends('layout')
@section('title','Daftar')
@section('content')
<x-account-shell title="Daftar" subtitle="Buat akun untuk mulai menyelamatkan makanan dan melihat dampak yang Anda berikan.">
 <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl" @submit="busy=true"
  x-data="{role:@js(old('role', $role)),pw:'',pc:'',show:false,busy:false,get score(){let s=0;if(this.pw.length>=6)s++;if(this.pw.length>=10)s++;if(/[A-Z]/.test(this.pw)&&/[a-z]/.test(this.pw))s++;if(/\d/.test(this.pw)&&/[^A-Za-z0-9]/.test(this.pw))s++;return s}}">@csrf

  <x-avatar-picker><p class="mt-3 text-xs text-slate-500">Foto profil (opsional, maks. 2 MB)</p></x-avatar-picker>

  <div class="mt-10 grid gap-x-10 gap-y-6 md:grid-cols-2">
   <div><label class="lbl text-base" for="n">Nama <span class="text-red-600">*</span></label>
    <input id="n" name="name" required maxlength="80" class="inp rounded-xl py-3" value="{{ old('name') }}" placeholder="Masukkan nama Anda" autocomplete="name" @error('name') aria-invalid="true" @enderror>
    @error('name')<p class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="role">Peran <span class="text-red-600">*</span></label>
    <select id="role" name="role" x-model="role" class="inp rounded-xl py-3" aria-describedby="role-hint">
     @foreach(\App\Models\User::ROLES as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
    </select>
    <p id="role-hint" class="hint" x-text="{provider:'Restoran, hotel, katering, atau event yang punya surplus makanan.',recipient:'Relawan, panti, atau dapur umum yang menyalurkan makanan layak.',processor:'Peternak maggot BSF atau komposter yang mengolah sisa organik.'}[role]"></p>
    @error('role')<p class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="o" x-text="role=='provider'?'Nama usaha / instansi':'Nama organisasi'">Nama organisasi</label>
    <input id="o" name="organization" required maxlength="80" class="inp rounded-xl py-3" value="{{ old('organization') }}" placeholder="Masukkan nama organisasi" @error('organization') aria-invalid="true" @enderror>
    @error('organization')<p class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="ph">No WhatsApp</label>
    <input id="ph" name="phone" maxlength="20" inputmode="tel" class="inp rounded-xl py-3" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon Anda" autocomplete="tel">
    <p class="hint">Agar pemberi &amp; penerima bisa saling menghubungi.</p></div>
   <div class="md:col-span-2"><label class="lbl text-base" for="em">Email <span class="text-red-600">*</span></label>
    <input id="em" name="email" type="email" required class="inp rounded-xl py-3" value="{{ old('email') }}" placeholder="Masukkan email Anda" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="em-err" @enderror>
    @error('email')<p id="em-err" class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="pw">Kata sandi <span class="text-red-600">*</span></label>
    <div class="relative"><input id="pw" name="password" :type="show?'text':'password'" required minlength="6" class="inp rounded-xl py-3 pr-24" x-model="pw" autocomplete="new-password" placeholder="Minimal 6 karakter">
     <button type="button" class="absolute inset-y-0 right-3 text-xs font-bold text-slate-500 hover:text-moss" @click="show=!show" :aria-pressed="show" x-text="show?'Sembunyikan':'Lihat'">Lihat</button></div>
    <div class="mt-2 flex gap-1" aria-hidden="true"><template x-for="k in 4"><span class="h-1 flex-1 rounded-full transition" :class="k<=score?(score<2?'bg-red-500':score<3?'bg-amber-500':'bg-moss'):'bg-slate-200'"></span></template></div>
    <p class="hint" x-show="pw" x-text="'Kekuatan: '+['Terlalu pendek','Lemah','Cukup','Kuat','Sangat kuat'][score]"></p>
    @error('password')<p class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="pc">Ulangi kata sandi <span class="text-red-600">*</span></label>
    <input id="pc" name="password_confirmation" :type="show?'text':'password'" required class="inp rounded-xl py-3" x-model="pc" autocomplete="new-password" placeholder="Ketik ulang kata sandi">
    <p x-show="pc" x-cloak class="hint" :class="pc==pw?'!text-moss':'!text-red-600'" x-text="pc==pw?'✓ Kata sandi cocok':'✗ Kata sandi belum cocok'"></p></div>
  </div>

  <div class="mt-10"><x-location-picker :lat="old('lat')" :lng="old('lng')" hint="Opsional. Dipakai sebagai titik awal di Peta dan form Lapor." /></div>

  <div class="mt-10 flex flex-col items-center gap-4 border-t border-slate-200 pt-8">
   <button class="btn btn-g w-full max-w-md py-3.5 text-sm normal-case tracking-normal" :disabled="busy" x-text="busy?'Membuat akun…':'Buat Akun'">Buat Akun</button>
   <p class="text-sm text-slate-600">Sudah punya akun? <a class="font-bold text-moss underline underline-offset-2" href="{{ route('login') }}">Masuk</a></p>
  </div>
 </form>
</x-account-shell>
@endsection
