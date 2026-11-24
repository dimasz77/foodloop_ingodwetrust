@extends('layout')
@section('title','Report Waste')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@section('content')
@php $K=\App\Services\ImpactCalculator::KG_PER_PORTION; $C=\App\Services\ImpactCalculator::CO2E_PER_KG; @endphp
<form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data" x-data="rep()" class="mx-auto max-w-5xl px-4 pt-8">@csrf
 <h1 class="h2">Report Waste</h1><p class="text-xs text-slate-600">Lengkapi informasi makanan berlebih yang ingin Anda laporkan</p>
 <div class="mt-5 grid gap-4 md:grid-cols-2">
  <div><label class="lbl" for="ti">Keterangan Makanan *</label><input id="ti" name="title" class="inp" required value="{{ old('title') }}" placeholder="Contoh: Makanan berlebih dari seminar kampus"></div>
  <div><label class="lbl" for="ex">Waktu Kedaluwarsa *</label><input id="ex" type="datetime-local" name="expires_at" class="inp" required value="{{ old('expires_at', now()->addHours(4)->format('Y-m-d\TH:i')) }}"></div>
  <div><label class="lbl" for="po">Jumlah Porsi</label><input id="po" type="number" min="1" name="portions" x-model="por" class="inp" placeholder="Masukkan porsi makanan"></div>
  <div><label class="lbl" for="kg">Berat (Opsional)</label><input id="kg" type="number" min="0.1" step="0.1" name="weight_kg" x-model="kg" class="inp" placeholder="kg"></div>
  <div><span class="lbl">Opsi Pengantaran</span><div class="flex gap-5 text-xs"><label><input type="radio" name="delivery" value="Self-pickup" x-model="delivery"> Self-pickup</label><label><input type="radio" name="delivery" value="Diantar" x-model="delivery"> Diantar</label></div>
   <label class="lbl mt-4" for="ds">Deskripsi (Opsional)</label><textarea id="ds" name="description" rows="4" maxlength="500" class="inp" x-on:input="len=$event.target.value.length" placeholder="Informasi tambahan tentang makanan (bahan, cara penyimpanan, dll)">{{ old('description') }}</textarea><div class="text-right text-[10px] text-slate-400"><span x-text="len"></span>/500</div></div>
  <div><label class="lbl" for="ph">Foto Makanan *</label><label for="ph" class="grid h-44 cursor-pointer place-items-center rounded-xl border-2 border-dashed border-slate-300 bg-white text-center text-xs text-slate-500">📤<br><b>Klik untuk upload foto</b><br>atau drag and drop<br>Format: JPG,PNG (Max. 5MB)</label><input id="ph" type="file" name="photo" accept="image/png,image/jpeg" capture="environment" class="mt-2 text-xs"></div></div>
 <h2 class="h2 mt-8">Feasibility Checklist</h2><p class="text-xs text-slate-600">Jawab pertanyaan berikut, sistem akan menentukan jalur terbaik untuk makanan Anda</p>
 <div class="mt-3">@foreach(['q1'=>'Disimpan pada suhu aman? (kurang dari 5°C atau 60°C)','q2'=>'Berbau asam/busuk?','q3'=>'Sudah lebih dari 4 jam di suhu ruang?','q4'=>'Kemasan masih baik dan bersih?'] as $k=>$q)
  <div class="flex items-center justify-between gap-3 border-b border-slate-300 py-3 text-xs"><span id="{{ $k }}l">{{ $q }}</span><span class="flex gap-2" role="radiogroup" aria-labelledby="{{ $k }}l">@foreach(['y'=>'YA','n'=>'TIDAK'] as $v=>$l)<label class="cursor-pointer rounded-md border px-3 py-1 font-bold" :class="{{ $k }}=='{{ $v }}'?'border-teal2 bg-teal2 text-white':'bg-white text-slate-700'"><input class="sr-only" type="radio" name="{{ $k }}" value="{{ $v }}" x-model="{{ $k }}">{{ $l }}</label>@endforeach</span></div>@endforeach
  <div class="mt-3 text-right"><button type="button" class="btn" @click="$refs.pick.scrollIntoView({behavior:'smooth'})">Selanjutnya ›</button></div></div>
 <section x-ref="pick" class="mt-8"><h2 class="h2">Pickup Location</h2><p class="text-xs text-slate-600">Pastikan lokasi dan kontak yang dapat dihubungi sudah benar</p>
  <div id="pm" x-init="initMap()" class="mt-3 h-64 overflow-hidden rounded-xl ring-1 ring-black/10" role="application" aria-label="Pilih lokasi penjemputan"></div>
  <p class="mt-1 text-[11px] text-slate-500">Geser pin atau klik peta untuk mengatur lokasi. Koordinat: <span x-text="(+lat).toFixed(5)+', '+(+lng).toFixed(5)"></span> · <button type="button" class="font-bold text-moss underline" @click="geo()">Gunakan lokasi saya</button></p>
  <input type="hidden" name="lat" :value="lat"><input type="hidden" name="lng" :value="lng">
  <h3 class="mt-5 text-sm font-extrabold">Kontak Food Provider *</h3>
  <div class="mt-2 grid gap-4 md:grid-cols-2"><div><label class="lbl" for="pt">Food Provider</label><select id="pt" name="provider_type" class="inp"><option>Hotel<option>Restoran<option>Katering<option>Event / Kampus</select></div>
   <div><label class="lbl" for="pn">Nama Food Provider *</label><input id="pn" name="provider_name" required class="inp" value="{{ old('provider_name', auth()->user()->organization ?? auth()->user()->name) }}" placeholder="Masukkan nama Food Provider"></div>
   <div><label class="lbl" for="wa">No WhatsApp</label><input id="wa" name="whatsapp" class="inp" inputmode="tel" value="{{ old('whatsapp', auth()->user()->phone) }}" placeholder="Masukkan nomor Anda"></div>
   <div><label class="lbl" for="al">Alergen/Diet (Opsional)</label><select id="al" name="allergen" class="inp"><option value="">Informasi tambahan<option>Tidak mengandung babi<option>Mengandung kacang<option>Vegetarian</select></div></div>
  <div class="mt-4 text-right"><button type="button" class="btn" :disabled="!done" @click="show=true;$nextTick(()=>$refs.res.scrollIntoView({behavior:'smooth'}))">Lihat Hasil &amp; Rute ›</button></div>
  <p x-show="!done" class="text-right text-[11px] text-slate-500">Jawab semua pertanyaan checklist terlebih dahulu.</p></section>
 <section x-ref="res" x-show="show && done" x-cloak class="mt-8" aria-live="polite"><h2 class="h2">Analysis Results and Route Recommendations</h2><p class="text-xs text-slate-600">Berdasarkan jawaban Anda, sistem merekomendasikan jalur berikut:</p>
  <div class="mx-auto mt-4 max-w-xl text-center"><div class="grid h-44 place-items-center rounded-xl text-7xl" :class="ok?'bg-green-100':'bg-amber-100'" x-text="ok?'🍱🤝':'🪱♻️'"></div>
   <h3 class="mt-3 font-extrabold" x-text="ok?'Jalur pangan (Human First)':'Jalur organik (Earth First)'"></h3>
   <p class="text-xs text-slate-500" x-text="ok?'Makanan Anda layak konsumsi dan akan disalurkan kepada penerima manfaat terdekat.':'Makanan tidak layak konsumsi dan akan dialihkan ke maggot BSF atau kompos agar tidak berakhir di TPA.'"></p>
   <p class="mt-3 text-[11px] text-slate-500" x-text="ok?'Penerima Potensial Terdekat:':'Pengolah Organik Terdekat:'"></p><p class="text-lg font-extrabold" x-text="near?near.name:'-'"></p><p class="text-xs text-slate-500" x-text="near?near.d.toFixed(1).replace('.',',')+' km dari lokasi Anda':''"></p>
   <p class="mt-2 text-xs">Estimasi dampak: <b x-text="pr+' porsi'"></b> · <b x-text="co2+' kg CO₂e'"></b> dicegah</p>
   <button class="btn mt-4">Kirim Laporan ›</button></div></section>
</form>
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script>
const kmd=(a,b,c,d)=>{const q=Math.PI/180,x=Math.sin((c-a)*q/2)**2+Math.cos(a*q)*Math.cos(c*q)*Math.sin((d-b)*q/2)**2;return 12742*Math.asin(Math.sqrt(x))};
function rep(){return{q1:'{{ old('q1') }}',q2:'{{ old('q2') }}',q3:'{{ old('q3') }}',q4:'{{ old('q4') }}',kg:'{{ old('weight_kg') }}',por:'{{ old('portions') }}',lat:{{ old('lat','-6.2088') }},lng:{{ old('lng','106.8456') }},delivery:'Diantar',show:false,len:0,pt:@json($partners),mk:null,
 get done(){return this.q1&&this.q2&&this.q3&&this.q4},get ok(){return this.q1=='y'&&this.q2=='n'&&this.q3=='n'&&this.q4=='y'},
 get w(){return this.kg?+this.kg:(this.por?this.por*{{ $K }}:0)},get co2(){return (this.w*{{ $C }}).toFixed(1).replace('.',',')},get pr(){return this.por?this.por:Math.max(0,Math.round(this.w/{{ $K }}))},
 get near(){const c=this.ok?'layak':'organik';return this.pt.filter(p=>p.type==c).map(p=>({...p,d:kmd(this.lat,this.lng,p.lat,p.lng)})).sort((a,b)=>a.d-b.d)[0]},
 initMap(){const m=L.map('pm').setView([this.lat,this.lng],12);L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(m);
  this.mk=L.marker([this.lat,this.lng],{draggable:true}).addTo(m);this.map=m;const set=ll=>{this.lat=+ll.lat.toFixed(6);this.lng=+ll.lng.toFixed(6)};
  this.mk.on('dragend',()=>set(this.mk.getLatLng()));m.on('click',e=>{this.mk.setLatLng(e.latlng);set(e.latlng)})},
 geo(){navigator.geolocation&&navigator.geolocation.getCurrentPosition(p=>{this.lat=+p.coords.latitude.toFixed(6);this.lng=+p.coords.longitude.toFixed(6);this.mk.setLatLng([this.lat,this.lng]);this.map.setView([this.lat,this.lng],14)})}}}
</script>@endpush
@endsection
