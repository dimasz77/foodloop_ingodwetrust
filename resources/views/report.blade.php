@extends('dash')
@section('title','Report Waste')
@section('content')
@php $K=\App\Services\ImpactCalculator::KG_PER_PORTION; $C=\App\Services\ImpactCalculator::CO2E_PER_KG; @endphp
<h1 class="text-3xl font-extrabold">Report Waste</h1><p class="mb-6 text-slate-600">Lengkapi informasi makanan berlebih yang ingin Anda laporkan.</p>
<form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2"
 x-data="{q1:'{{ old('q1') }}',q2:'{{ old('q2') }}',q3:'{{ old('q3') }}',q4:'{{ old('q4') }}',kg:'{{ old('weight_kg') }}',por:'{{ old('portions') }}',lat:'{{ old('lat','-6.2088') }}',lng:'{{ old('lng','106.8456') }}',
 get done(){return this.q1&&this.q2&&this.q3&&this.q4},get ok(){return this.q1=='y'&&this.q2=='n'&&this.q3=='n'&&this.q4=='y'},
 get w(){return this.kg?+this.kg:(this.por?this.por*{{ $K }}:0)},get co2(){return (this.w*{{ $C }}).toFixed(1).replace('.',',')},get pr(){return this.por?this.por:Math.max(0,Math.round(this.w/{{ $K }}))},
 geo(){navigator.geolocation&&navigator.geolocation.getCurrentPosition(p=>{this.lat=p.coords.latitude.toFixed(6);this.lng=p.coords.longitude.toFixed(6)})}}">
 @csrf
 <div><label class="lbl" for="pn">Nama Penyedia *</label><input id="pn" class="inp" name="provider_name" value="{{ old('provider_name') }}" required placeholder="Contoh: Hotel Sun"></div>
 <div><label class="lbl" for="ti">Keterangan Makanan *</label><input id="ti" class="inp" name="title" value="{{ old('title') }}" required placeholder="Contoh: Makanan berlebih dari seminar kampus"></div>
 <div><label class="lbl" for="ex">Waktu Kedaluwarsa *</label><input id="ex" type="datetime-local" class="inp" name="expires_at" value="{{ old('expires_at', now()->addHours(4)->format('Y-m-d\TH:i')) }}" required></div>
 <div class="grid grid-cols-2 gap-2"><div><label class="lbl" for="po">Jumlah Porsi</label><input id="po" type="number" min="1" class="inp" name="portions" x-model="por"></div><div><label class="lbl" for="kg">Berat (kg)</label><input id="kg" type="number" min="0.1" step="0.1" class="inp" name="weight_kg" x-model="kg"></div></div>
 <div><label class="lbl" for="la">Latitude *</label><input id="la" class="inp" name="lat" x-model="lat" required></div>
 <div><label class="lbl" for="ln">Longitude *</label><input id="ln" class="inp" name="lng" x-model="lng" required><button type="button" class="mt-2 text-sm font-semibold text-emerald-700" @click="geo()">📍 Gunakan lokasi saya</button></div>
 <div class="md:col-span-2"><label class="lbl" for="ds">Deskripsi (opsional)</label><textarea id="ds" rows="2" class="inp" name="description">{{ old('description') }}</textarea></div>
 <div class="md:col-span-2"><label class="lbl" for="ph">Foto Makanan (JPG/PNG, maks. 5 MB)</label><input id="ph" type="file" name="photo" accept="image/*" class="inp"></div>
 <fieldset class="card md:col-span-2"><legend class="px-1 text-lg font-bold">Feasibility Checklist</legend>
  @foreach(['q1'=>'Disimpan pada suhu aman? (di bawah 5°C atau di atas 60°C)','q2'=>'Berbau asam atau busuk?','q3'=>'Sudah lebih dari 4 jam di suhu ruang?','q4'=>'Kemasan masih baik dan bersih?'] as $k=>$q)
  <div class="flex items-center justify-between gap-4 border-b py-3"><span>{{ $q }}</span><span class="flex gap-4"><label><input type="radio" name="{{ $k }}" value="y" x-model="{{ $k }}"> Ya</label><label><input type="radio" name="{{ $k }}" value="n" x-model="{{ $k }}"> Tidak</label></span></div>@endforeach
 </fieldset>
 <div class="md:col-span-2" aria-live="polite">
  <template x-if="done"><div class="card" :class="ok?'border-l-4 border-l-emerald-600':'border-l-4 border-l-amber-500'">
   <b x-text="ok?'Jalur Pangan (Human First)':'Jalur Organik (Earth First)'"></b>
   <p class="text-sm text-slate-600" x-text="ok?'Makanan layak dan akan disalurkan ke penerima manfaat terdekat.':'Makanan dialihkan ke mitra maggot BSF atau kompos terdekat agar tidak berakhir di TPA.'"></p>
   <p class="mt-1 text-sm">Estimasi dampak awal: <b x-text="pr+' porsi'"></b> · <b x-text="co2+' kg CO₂e'"></b> dicegah. Mitra terdekat ditetapkan otomatis saat laporan dikirim.</p></div></template></div>
 <div class="md:col-span-2"><button class="btn" :disabled="!done">Kirim Laporan</button></div>
</form>
@endsection
