@extends('layout')
@section('title','Laporkan Surplus')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"><style>.fl-pin{background:none;border:0}</style>@endpush
@section('content')
@php $K=\App\Services\ImpactCalculator::KG_PER_PORTION; $C=\App\Services\ImpactCalculator::CO2E_PER_KG; @endphp
<x-page-hero image="lapor.jpg" eyebrow="Lapor Surplus" title="Laporkan Surplus Makanan" subtitle="Kurang dari 1 menit. Jawab checklist, dan sistem menentukan jalur terbaik: ke manusia atau ke pengolah organik." :crumbs="['Lapor' => null]" />
<form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data" x-data="rep()" @submit="sending=true" class="pull-up mx-auto max-w-6xl px-4">@csrf

 {{-- Indikator progres (Heuristik #1: visibilitas status sistem) --}}
 <ol class="sticky top-[4.75rem] z-[900] grid grid-cols-4 gap-2 rounded-2xl bg-white/95 p-3 text-[0.6875rem] font-bold shadow-xl ring-1 ring-black/5 backdrop-blur" aria-label="Progres pengisian">
  @foreach(['Detail Makanan', 'Checklist', 'Lokasi & Kontak', 'Hasil'] as $i => $s)
   <li><div class="h-1.5 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-moss transition-all duration-500" :style="'width:'+steps[{{ $i }}]+'%'"></div></div><span class="mt-1 block truncate" :class="steps[{{ $i }}]==100?'text-moss':'text-slate-400'"><span x-text="steps[{{ $i }}]==100?'✔':'{{ $i + 1 }}'"></span> {{ $s }}</span></li>
  @endforeach
  <li class="col-span-4 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-2"><span class="text-slate-500">Kolom bertanda <span class="text-red-600">*</span> wajib diisi</span>
   <span class="chip" :class="done?(ok?'bg-green-100 text-green-800':'bg-amber-100 text-amber-800'):'bg-slate-100 text-slate-500'" x-text="done?(ok?'Prediksi jalur: Human-First':'Prediksi jalur: Earth-First'):'Prediksi jalur muncul setelah checklist'"></span></li>
 </ol>

 <section class="card mt-5 grid gap-4 md:grid-cols-2">
  <h2 class="sec md:col-span-2"><span class="grid h-6 w-6 place-items-center rounded-full bg-moss text-[0.75rem] tracking-normal text-white">1</span>Detail Makanan</h2>
  <div><label class="lbl" for="ti">Keterangan Makanan <span class="text-red-600">*</span></label><input id="ti" name="title" class="inp" required maxlength="120" x-model="title" placeholder="Contoh: Nasi kotak dari seminar kampus" @error('title') aria-invalid="true" aria-describedby="ti-err" @enderror>
   @error('title')<p id="ti-err" class="err">⚠ {{ $message }}</p>@enderror
   <div class="mt-2 flex flex-wrap gap-1">@foreach(['🍱 Nasi kotak', '🍞 Roti & kue', '🥗 Prasmanan', '🥦 Sisa sayur & buah'] as $s)<button type="button" class="chip bg-slate-100 text-slate-600 transition hover:bg-moss hover:text-white" @click="title='{{ mb_substr($s, 2) }}'">{{ $s }}</button>@endforeach</div></div>
  <div><label class="lbl" for="ex">Waktu Kedaluwarsa <span class="text-red-600">*</span></label><input id="ex" type="datetime-local" name="expires_at" class="inp" required min="{{ now()->format('Y-m-d\TH:i') }}" x-model="exp" :aria-invalid="hoursLeft<=0" @error('expires_at') aria-describedby="ex-err" @enderror>
   @error('expires_at')<p id="ex-err" class="err">⚠ {{ $message }}</p>@enderror
   <p x-show="hoursLeft<=0" x-cloak class="err">⚠ Waktu sudah lewat. Pilih waktu di masa depan.</p>
   <div class="mt-2 flex flex-wrap gap-1">@foreach([1, 2, 4, 8] as $h)<button type="button" class="chip bg-slate-100 text-slate-600 transition hover:bg-moss hover:text-white" @click="setExp({{ $h }})">+{{ $h }} jam</button>@endforeach<span class="chip" :class="hoursLeft<2?'bg-red-100 text-red-700':'bg-green-100 text-green-800'" x-text="hoursLeft>0?'± '+hoursLeft.toFixed(1).replace('.',',')+' jam lagi'+(hoursLeft<2?' · darurat':''):'Sudah lewat'"></span></div></div>
  <div class="grid grid-cols-2 gap-3"><div><label class="lbl" for="po">Jumlah Porsi</label><input id="po" type="number" min="1" name="portions" x-model="por" class="inp" placeholder="cth. 30"></div>
   <div><label class="lbl" for="kg">Berat (kg)</label><input id="kg" type="number" min="0.1" step="0.1" name="weight_kg" x-model="kg" class="inp" placeholder="cth. 12"></div>
   <p class="hint col-span-2 -mt-1">Isi salah satu, yang lain dihitung otomatis. 1 porsi ≈ {{ str_replace('.', ',', $K) }} kg.</p>
   @error('weight_kg')<p class="err col-span-2 -mt-2">⚠ {{ $message }}</p>@enderror
   <div class="col-span-2"><span class="lbl">Opsi Pengantaran</span><div class="grid grid-cols-2 gap-2 text-xs">@foreach(['Self-pickup' => '🏃 Diambil penerima', 'Diantar' => '🚚 Saya antar'] as $v => $l)<label class="cursor-pointer rounded-lg border px-3 py-2 text-center font-bold transition" :class="delivery=='{{ $v }}'?'border-moss bg-moss/10 text-moss':'border-slate-200'"><input type="radio" name="delivery" value="{{ $v }}" x-model="delivery" class="sr-only">{{ $l }}</label>@endforeach</div></div>
   <div class="col-span-2"><label class="lbl" for="ds">Deskripsi (Opsional)</label><textarea id="ds" name="description" rows="3" maxlength="500" class="inp" x-model="desc" placeholder="Bahan, cara penyimpanan, dll."></textarea><div class="text-right text-[0.6875rem]" :class="desc.length>450?'text-red-600':'text-slate-400'"><span x-text="desc.length"></span>/500</div></div></div>
  <div><span class="lbl">Foto Makanan (disarankan)</span>
   <label for="ph" @dragover.prevent="drag=true" @dragleave.prevent="drag=false" @drop.prevent="drop($event)" class="relative grid h-64 cursor-pointer place-items-center overflow-hidden rounded-xl border-2 border-dashed text-center text-xs text-slate-500 transition" :class="drag?'border-moss bg-moss/5 scale-[1.01]':'border-slate-300 bg-slate-50 hover:border-moss'">
    <template x-if="photo"><img :src="photo" alt="Pratinjau foto makanan" class="absolute inset-0 h-full w-full object-cover"></template>
    <span x-show="!photo"><span class="text-3xl">📤</span><br><b>Klik atau seret foto ke sini</b><br>JPG/PNG, maks. 5MB</span>
    <span x-show="photo" x-cloak class="absolute bottom-2 right-2 rounded-lg bg-white/90 px-2 py-1 text-[0.6875rem] font-bold shadow">Ganti foto</span></label>
   <input id="ph" x-ref="file" type="file" name="photo" accept="image/png,image/jpeg" capture="environment" class="sr-only" @change="preview($event.target.files[0])">
   <p x-show="photoErr" x-cloak class="mt-1 text-[0.75rem] text-red-600" x-text="photoErr"></p></div>
 </section>

 <section class="card mt-6"><h2 class="sec"><span class="grid h-6 w-6 place-items-center rounded-full bg-moss text-[0.75rem] tracking-normal text-white">2</span>Checklist Kelayakan</h2><p class="mt-1 text-xs text-slate-600">Jawab pertanyaan berikut. Satu jawaban berisiko akan mengarahkan makanan ke jalur organik.</p>
  <div class="mt-3">@foreach(['q1' => ['🌡️', 'Disimpan pada suhu aman? (di bawah 5°C atau di atas 60°C)', 'y'], 'q2' => ['👃', 'Berbau asam/busuk?', 'n'], 'q3' => ['⏳', 'Sudah lebih dari 4 jam di suhu ruang?', 'n'], 'q4' => ['📦', 'Kemasan masih baik dan bersih?', 'y']] as $k => [$ic, $q, $safe])
   <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 py-3 text-xs last:border-0"><span id="{{ $k }}l" class="flex items-center gap-2"><span class="grid h-8 w-8 place-items-center rounded-lg bg-slate-50 text-base">{{ $ic }}</span>{{ $q }}<span x-show="{{ $k }}" x-cloak x-text="{{ $k }}=='{{ $safe }}'?'✅':'⚠️'"></span></span>
    <span class="flex gap-2" role="radiogroup" aria-labelledby="{{ $k }}l">@foreach(['y' => 'YA', 'n' => 'TIDAK'] as $v => $l)<label class="cursor-pointer rounded-md border px-4 py-1.5 font-bold transition has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-moss/40" :class="{{ $k }}=='{{ $v }}'?'border-teal2 bg-teal2 text-white shadow':'bg-white text-slate-700 hover:border-teal2'"><input class="sr-only" type="radio" name="{{ $k }}" value="{{ $v }}" x-model="{{ $k }}" required>{{ $l }}</label>@endforeach</span></div>
  @endforeach</div>
 </section>

 <section x-ref="pick" class="card mt-6 scroll-mt-32"><h2 class="sec"><span class="grid h-6 w-6 place-items-center rounded-full bg-moss text-[0.75rem] tracking-normal text-white">3</span>Lokasi &amp; Kontak</h2><p class="mt-1 text-xs text-slate-600">Geser pin atau klik peta. Garis putus-putus menunjukkan mitra tujuan terdekat.</p>
  <div id="pm" x-init="initMap()" class="mt-3 h-72 overflow-hidden rounded-xl ring-1 ring-black/10" role="application" aria-label="Pilih lokasi penjemputan"></div>
  <p class="mt-1 text-[0.75rem] text-slate-500">Koordinat: <span x-text="(+lat).toFixed(5)+', '+(+lng).toFixed(5)"></span> · <button type="button" class="font-bold text-moss underline" @click="geo()" x-text="locating?'Mencari lokasi…':'🎯 Gunakan lokasi saya'"></button></p>
  <input type="hidden" name="lat" :value="lat"><input type="hidden" name="lng" :value="lng">
  <h3 class="mt-5 text-sm font-extrabold">Kontak Food Provider</h3>
  <div class="mt-2 grid gap-4 md:grid-cols-2"><div><label class="lbl" for="pt">Jenis Food Provider</label><select id="pt" name="provider_type" class="inp">@foreach(['Hotel', 'Restoran', 'Katering', 'Event / Kampus', 'Toko Roti'] as $o)<option @selected(old('provider_type') == $o)>{{ $o }}</option>@endforeach</select></div>
   <div><label class="lbl" for="pn">Nama Food Provider <span class="text-red-600">*</span></label><input id="pn" name="provider_name" required class="inp" value="{{ old('provider_name', auth()->user()->organization ?? auth()->user()->name) }}"></div>
   <div><label class="lbl" for="wa">No WhatsApp</label><input id="wa" name="whatsapp" class="inp" inputmode="tel" value="{{ old('whatsapp', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx"></div>
   <div><label class="lbl" for="al">Alergen/Diet (Opsional)</label><select id="al" name="allergen" class="inp"><option value="">Tidak ada informasi</option>@foreach(['Tidak mengandung babi', 'Mengandung kacang', 'Vegetarian', 'Mengandung seafood'] as $o)<option @selected(old('allergen') == $o)>{{ $o }}</option>@endforeach</select></div></div>
 </section>

 <section x-ref="res" class="mt-6" aria-live="polite">
  <div x-show="!done" class="card text-center text-xs text-slate-500">✍️ Jawab semua pertanyaan checklist untuk melihat hasil analisis dan rute rekomendasi.</div>
  <div x-show="done" x-cloak x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-4" class="overflow-hidden rounded-2xl shadow-lg ring-1 ring-black/5">
   <div class="grid items-center gap-6 p-6 text-white md:grid-cols-[auto_1fr]" :class="ok?'bg-gradient-to-br from-moss to-teal2':'bg-gradient-to-br from-amber-500 to-orange-600'">
    <div class="grid h-28 w-28 place-items-center rounded-full bg-white/15 text-6xl" x-text="ok?'🍱':'🪱'"></div>
    <div><span class="chip bg-white/20">Hasil Analisis</span><h2 class="mt-1 text-2xl font-extrabold" x-text="ok?'Jalur Pangan (Human-First)':'Jalur Organik (Earth-First)'"></h2>
     <p class="mt-1 text-xs text-white/85" x-text="ok?'Makanan Anda layak konsumsi dan akan disalurkan kepada penerima manfaat terdekat.':'Makanan akan dialihkan ke maggot BSF atau kompos agar tidak berakhir di TPA.'"></p></div></div>
   <div class="grid gap-4 bg-white p-6 text-center sm:grid-cols-3">
    <div><div class="text-[0.75rem] text-slate-500" x-text="ok?'Penerima terdekat':'Pengolah organik terdekat'"></div><b class="block text-sm" x-text="near?near.name:'-'"></b><span class="text-xs text-slate-500" x-text="near?near.d.toFixed(1).replace('.',',')+' km':''"></span></div>
    <div><div class="text-[0.75rem] text-slate-500">Estimasi porsi</div><b class="text-2xl text-moss" x-text="pr"></b></div>
    <div><div class="text-[0.75rem] text-slate-500">CO₂e dicegah</div><b class="text-2xl text-moss" x-text="co2+' kg'"></b></div>
   </div>
   <div class="flex flex-wrap items-center justify-between gap-3 border-t bg-slate-50 px-6 py-4"><p class="text-[0.75rem] text-slate-500" x-text="w?'Siap dikirim. Penerima di sekitar akan melihat laporan Anda di peta.':'Isi jumlah porsi atau berat terlebih dahulu.'"></p>
    <button class="btn btn-g px-6 py-3 text-sm" :disabled="sending||!w"><span x-show="!sending">Kirim Laporan ›</span><span x-show="sending" x-cloak>⏳ Mengirim…</span></button></div>
  </div>
 </section>
 <x-help class="mt-6" title="Bagaimana sistem menentukan jalur?">
  <p><b>Human-First</b>: semua jawaban checklist aman (suhu aman, tidak berbau, belum lebih dari 4 jam di suhu ruang, kemasan baik). Makanan ditampilkan di peta untuk penerima terdekat.</p>
  <p><b>Earth-First</b>: ada satu jawaban berisiko. Makanan diarahkan ke pengolah maggot BSF atau kompos.</p>
  <p>Jika makanan layak tidak diklaim sampai batas waktu, sistem <b>otomatis mengalihkannya</b> ke jalur organik. Anda bisa memantau semuanya di halaman <a class="font-bold text-moss underline" href="{{ route('status') }}">Status Laporan</a>.</p>
 </x-help>
</form>
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script>
const kmd=(a,b,c,d)=>{const q=Math.PI/180,x=Math.sin((c-a)*q/2)**2+Math.cos(a*q)*Math.cos(c*q)*Math.sin((d-b)*q/2)**2;return 12742*Math.asin(Math.sqrt(x))};
const localIso=d=>{const p=n=>String(n).padStart(2,'0');return `${d.getFullYear()}-${p(d.getMonth()+1)}-${p(d.getDate())}T${p(d.getHours())}:${p(d.getMinutes())}`};
function rep(){return{q1:@json(old('q1', '')),q2:@json(old('q2', '')),q3:@json(old('q3', '')),q4:@json(old('q4', '')),kg:@json(old('weight_kg', '')),por:@json(old('portions', '')),
 title:@json(old('title', '')),desc:@json(old('description', '')),exp:@json(old('expires_at', now()->addHours(4)->format('Y-m-d\TH:i'))),
 lat:{{ (float) old('lat', -6.2088) }},lng:{{ (float) old('lng', 106.8456) }},delivery:@json(old('delivery', 'Diantar')),pt:@json($partners),mk:null,line:null,photo:null,photoErr:'',drag:false,sending:false,locating:false,now:Date.now(),
 get dirty(){return !!(this.title||this.por||this.kg||this.desc||this.photo)},
 init(){window.addEventListener("beforeunload",e=>{if(this.dirty&&!this.sending){e.preventDefault();e.returnValue=""}});setInterval(()=>this.now=Date.now(),30000);this.$watch('done',v=>v&&this.$nextTick(()=>this.$refs.res.scrollIntoView({behavior:'smooth',block:'center'})));['lat','lng','q1','q2','q3','q4'].forEach(k=>this.$watch(k,()=>this.drawLine()))},
 get done(){return !!(this.q1&&this.q2&&this.q3&&this.q4)},get ok(){return this.q1=='y'&&this.q2=='n'&&this.q3=='n'&&this.q4=='y'},
 get w(){return this.kg?+this.kg:(this.por?this.por*{{ $K }}:0)},get co2(){return (this.w*{{ $C }}).toFixed(1).replace('.',',')},get pr(){return this.por?+this.por:Math.max(0,Math.round(this.w/{{ $K }}))},
 get hoursLeft(){return (new Date(this.exp)-this.now)/36e5},
 get steps(){const a=[!!this.title,!!this.exp,!!this.w].filter(Boolean).length,b=[this.q1,this.q2,this.q3,this.q4].filter(Boolean).length;return [Math.round(a/3*100),b*25,this.done?100:(this.lat?50:0),this.done&&this.w?100:0]},
 get near(){const c=this.ok?'layak':'organik';return this.pt.filter(p=>p.type==c).map(p=>({...p,d:kmd(this.lat,this.lng,p.lat,p.lng)})).sort((a,b)=>a.d-b.d)[0]},
 setExp(h){this.exp=localIso(new Date(Date.now()+h*36e5))},
 preview(f){this.photoErr='';if(!f){this.photo=null;return}if(f.size>5*1024*1024){this.photoErr='Ukuran foto melebihi 5MB.';this.$refs.file.value='';this.photo=null;return}this.photo=URL.createObjectURL(f)},
 drop(e){this.drag=false;const f=e.dataTransfer.files[0];if(!f||!f.type.startsWith('image/'))return;const dt=new DataTransfer();dt.items.add(f);this.$refs.file.files=dt.files;this.preview(f)},
 initMap(){const m=L.map('pm').setView([this.lat,this.lng],12);L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(m);
  this.pt.forEach(p=>L.marker([p.lat,p.lng],{icon:L.divIcon({className:'fl-pin',iconSize:[26,26],iconAnchor:[13,13],html:`<span class="grid h-[26px] w-[26px] place-items-center rounded-full bg-white text-xs shadow ring-1 ring-black/10">${p.type=='layak'?'🏠':'♻️'}</span>`})}).bindTooltip(p.name).addTo(m));
  this.mk=L.marker([this.lat,this.lng],{draggable:true}).addTo(m);this.map=m;const set=ll=>{this.lat=+ll.lat.toFixed(6);this.lng=+ll.lng.toFixed(6)};
  this.mk.on('dragend',()=>set(this.mk.getLatLng()));m.on('click',e=>{this.mk.setLatLng(e.latlng);set(e.latlng)});this.drawLine()},
 drawLine(){if(!this.map)return;this.line&&this.line.remove();const n=this.near;if(!n)return;this.line=L.polyline([[this.lat,this.lng],[n.lat,n.lng]],{color:this.done&&!this.ok?'#f59e0b':'#1f6b3a',weight:3,dashArray:'6 6'}).addTo(this.map).bindTooltip('→ '+n.name)},
 geo(){if(!navigator.geolocation)return;this.locating=true;navigator.geolocation.getCurrentPosition(p=>{this.locating=false;this.lat=+p.coords.latitude.toFixed(6);this.lng=+p.coords.longitude.toFixed(6);this.mk.setLatLng([this.lat,this.lng]);this.map.flyTo([this.lat,this.lng],14)},()=>this.locating=false)}}}
</script>@endpush
@endsection
