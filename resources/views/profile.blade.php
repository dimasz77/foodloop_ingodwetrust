@extends('layout')
@section('title','Profil')
@section('content')
<x-account-shell title="Profil" subtitle="Kelola informasi akun dan lihat dampak yang telah Anda berikan.">
 <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl" x-data="{busy:false}" @submit="busy=true">
  @csrf @method('PUT')

  <x-avatar-picker :src="$user->avatarUrl()" :initial="mb_strtoupper(mb_substr($user->name, 0, 1))">
   <p class="mt-4 text-base font-extrabold text-[#173a39]">{{ $user->name }}</p>
   <span class="chip mt-1 bg-lime-100 px-3 py-1 text-[#0c1f19]">{{ $user->roleLabel() }} · {{ $journey['level'] }}</span>
  </x-avatar-picker>

  {{-- Ringkasan dampak --}}
  <dl class="mt-8 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl bg-white text-center shadow-sm ring-1 ring-black/5">
   @foreach([[$portions, 0, 'porsi'], [$journey['kg'], 1, 'kg tersalurkan'], [\App\Services\ImpactCalculator::co2e($journey['kg']), 1, 'kg CO₂e dicegah']] as [$v, $d, $l])
    <div class="px-2 py-4"><dd class="text-2xl font-extrabold text-moss">{{ number_format($v, $d, ',', '.') }}</dd><dt class="text-[0.75rem] font-semibold text-slate-500">{{ $l }}</dt></div>
   @endforeach
  </dl>
  <p class="mt-2 text-center text-sm"><a href="{{ route('history') }}" class="font-bold text-moss underline underline-offset-2">Lihat riwayat &amp; lencana →</a></p>

  <div class="mt-10 grid gap-x-10 gap-y-6 md:grid-cols-2">
   <div><label class="lbl text-base" for="name">Nama <span class="text-red-600">*</span></label>
    <input id="name" name="name" required maxlength="80" class="inp rounded-xl py-3" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama Anda" autocomplete="name" @error('name') aria-invalid="true" @enderror>
    @error('name')<p class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="role">Peran</label>
    <select id="role" class="inp rounded-xl bg-slate-50 py-3" disabled aria-describedby="role-hint">@foreach(\App\Models\User::ROLES as $key => $label)<option @selected($user->role === $key)>{{ $label }}</option>@endforeach</select>
    <p id="role-hint" class="hint">Peran dikunci agar riwayat laporan dan klaim tetap konsisten.</p></div>
   <div><label class="lbl text-base" for="organization">{{ $user->isProvider() ? 'Nama usaha / instansi' : 'Nama organisasi' }} <span class="text-red-600">*</span></label>
    <input id="organization" name="organization" required maxlength="80" class="inp rounded-xl py-3" value="{{ old('organization', $user->organization) }}" placeholder="Masukkan nama organisasi" @error('organization') aria-invalid="true" @enderror>
    @error('organization')<p class="err">⚠ {{ $message }}</p>@enderror</div>
   <div><label class="lbl text-base" for="phone">No WhatsApp</label>
    <input id="phone" name="phone" maxlength="20" inputmode="tel" class="inp rounded-xl py-3" value="{{ old('phone', $user->phone) }}" placeholder="Masukkan nomor telepon Anda" autocomplete="tel">
    <p class="hint">Dipakai tombol "Hubungi via WhatsApp" setelah klaim.</p></div>
   <div class="md:col-span-2"><label class="lbl text-base" for="email">Email <span class="text-red-600">*</span></label>
    <input id="email" name="email" type="email" required class="inp rounded-xl py-3" value="{{ old('email', $user->email) }}" placeholder="Masukkan email Anda" autocomplete="email" @error('email') aria-invalid="true" @enderror>
    @error('email')<p class="err">⚠ {{ $message }}</p>@enderror</div>
  </div>

  <div class="mt-10"><x-location-picker :lat="old('lat', $user->lat)" :lng="old('lng', $user->lng)" hint="Dipakai sebagai titik awal di Peta dan form Lapor." /></div>

  <div class="mt-10 flex flex-wrap items-center justify-center gap-3 border-t border-slate-200 pt-8">
   <a href="{{ route('profile.edit') }}" class="btn btn-w px-6 py-3">Batal</a>
   <button class="btn btn-g w-full max-w-xs py-3.5 text-sm normal-case tracking-normal" :disabled="busy" x-text="busy ? 'Menyimpan…' : 'Simpan Profil'">Simpan Profil</button>
  </div>
 </form>
 <form method="POST" action="{{ route('logout') }}" class="mt-8 text-center">@csrf<button class="text-sm font-bold text-red-600 hover:underline">Keluar dari akun</button></form>
</x-account-shell>
@endsection
