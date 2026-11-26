{{--
  Pemilih lokasi: peta + pencarian alamat (OpenStreetMap Nominatim) + "Gunakan lokasi saat ini".
  Mengirim input tersembunyi lat & lng (kosong bila belum dipilih).
--}}
@props(['lat' => null, 'lng' => null, 'hint' => null])
@php
  $saved = $lat !== null && $lng !== null;
  $start = ['lat' => $saved ? (float) $lat : -6.2088, 'lng' => $saved ? (float) $lng : 106.8456, 'saved' => $saved];
  $mapId = 'lp-'.\Illuminate\Support\Str::random(6);
@endphp
<div x-data="locationPicker({{ json_encode($start) }}, '{{ $mapId }}')">
 <div class="flex flex-wrap items-end justify-between gap-2">
  <span class="lbl !mb-0 text-base">Lokasi</span>
  <span class="text-[0.75rem]" :class="saved?'text-moss font-semibold':'text-slate-500'" x-text="saved ? 'Dipilih: '+(+lat).toFixed(5)+', '+(+lng).toFixed(5) : 'Belum dipilih (opsional)'"></span>
 </div>
 @if($hint)<p class="hint !mt-0">{{ $hint }}</p>@endif
 <div class="relative mt-3 overflow-hidden rounded-3xl shadow-lg ring-1 ring-black/10">
  <div id="{{ $mapId }}" x-init="initMap()" class="h-80" role="application" aria-label="Peta untuk memilih lokasi"></div>
  <div class="absolute inset-x-3 top-4 z-[500] mx-auto max-w-md" @click.outside="results=[]">
   <div class="rounded-2xl bg-white p-3 shadow-xl">
    <label for="{{ $mapId }}-q" class="sr-only">Cari lokasi</label>
    <div class="relative"><svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5"/></svg>
     <input id="{{ $mapId }}-q" type="search" x-model="q" @input.debounce.600ms="search()" @keydown.enter.prevent="search()" class="inp border-transparent bg-slate-100 pl-9" placeholder="Ketik lokasi Anda…" autocomplete="off" role="combobox" :aria-expanded="results.length>0" aria-controls="{{ $mapId }}-r"></div>
    <p x-show="searching" x-cloak class="px-2 pt-2 text-xs text-slate-500" role="status">Mencari…</p>
    <ul id="{{ $mapId }}-r" x-show="results.length" x-cloak class="mt-1 max-h-52 overflow-y-auto" role="listbox">
     <template x-for="r in results" :key="r.place_id"><li><button type="button" role="option" @click="choose(r)" class="w-full rounded-lg px-3 py-2 text-left text-xs hover:bg-slate-100" x-text="r.display_name"></button></li></template>
    </ul>
    <p x-show="!searching && searched && !results.length" x-cloak class="px-2 pt-2 text-xs text-slate-500">Lokasi tidak ditemukan. Coba nama kota atau kelurahan.</p>
   </div>
  </div>
 </div>
 <input type="hidden" name="lat" :value="saved ? lat : ''"><input type="hidden" name="lng" :value="saved ? lng : ''">
 @error('lat')<p class="err">⚠ {{ $message }}</p>@enderror
 <div class="mt-5 flex justify-center">
  <button type="button" @click="geo()" class="btn w-full max-w-md bg-[#173a39] py-3 normal-case tracking-normal" :disabled="locating">
   <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="8" cy="8" r="3"/><path d="M8 1v2.5M8 12.5V15M1 8h2.5M12.5 8H15"/></svg>
   <span x-text="locating ? 'Mencari lokasi…' : 'Gunakan lokasi saat ini'">Gunakan lokasi saat ini</span></button>
 </div>
 <p x-show="geoErr" x-cloak class="err justify-center" x-text="geoErr"></p>
</div>

@once
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function locationPicker(start,mapId){return{lat:start.lat,lng:start.lng,saved:start.saved,q:'',results:[],searching:false,searched:false,locating:false,geoErr:'',map:null,mk:null,
 initMap(){this.map=L.map(mapId,{zoomControl:false}).setView([this.lat,this.lng],this.saved?15:11);L.control.zoom({position:'bottomright'}).addTo(this.map);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(this.map);
  this.mk=L.marker([this.lat,this.lng],{draggable:true,opacity:this.saved?1:.55}).addTo(this.map);
  this.mk.on('dragend',()=>this.set(this.mk.getLatLng()));this.map.on('click',e=>this.set(e.latlng))},
 set(ll,zoom){this.lat=+(+ll.lat).toFixed(6);this.lng=+(+ll.lng).toFixed(6);this.saved=true;this.mk.setLatLng([this.lat,this.lng]).setOpacity(1);zoom&&this.map.flyTo([this.lat,this.lng],zoom)},
 async search(){const q=this.q.trim();if(q.length<3){this.results=[];this.searched=false;return}this.searching=true;
  try{const r=await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=id&accept-language=id&q='+encodeURIComponent(q));this.results=r.ok?await r.json():[]}catch(e){this.results=[]}
  this.searching=false;this.searched=true},
 choose(r){this.set({lat:r.lat,lng:r.lon},15);this.q=r.display_name;this.results=[]},
 geo(){this.geoErr='';if(!navigator.geolocation){this.geoErr='Browser tidak mendukung lokasi.';return}this.locating=true;
  navigator.geolocation.getCurrentPosition(p=>{this.locating=false;this.set({lat:p.coords.latitude,lng:p.coords.longitude},16)},()=>{this.locating=false;this.geoErr='Izin lokasi ditolak. Cari lokasi secara manual di peta.'},{enableHighAccuracy:true,timeout:10000})}}}
</script>@endpush
@endonce
