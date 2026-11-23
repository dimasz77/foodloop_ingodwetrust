@extends('layout')
@section('title','Peta')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@section('content')
<h1 class="text-3xl font-extrabold">Interactive Rescue Map</h1><p class="mb-4 text-slate-600">Temukan makanan surplus di sekitar Anda dan klaim sebelum waktu habis.</p>
<div class="mb-3 flex flex-wrap gap-2"><input id="q" type="search" class="inp max-w-xs" placeholder="Cari lokasi atau makanan…" aria-label="Cari"><select id="c" class="inp max-w-[11rem]" aria-label="Kategori"><option value="">Semua Kategori<option value="layak">Layak Konsumsi<option value="organik">Organik</select><select id="r" class="inp max-w-[9rem]" aria-label="Radius"><option value="999">Semua jarak<option value="5">5 km<option value="10">10 km</select><label class="flex items-center gap-1 text-sm"><input type="checkbox" id="u"> Darurat</label></div>
<div class="grid gap-4 lg:grid-cols-3"><div class="lg:col-span-2"><div id="map" class="h-[460px] rounded-2xl border border-slate-200" role="application" aria-label="Peta makanan surplus"></div>
<p class="mt-2 flex flex-wrap gap-4 text-sm text-slate-600"><span>🟢 Layak</span><span>🟠 Organik</span><span>🔴 Segera ambil</span><span>🔵 Lokasi Anda</span><span>🏠 Penerima</span><span>♻️ Pengolah</span></p></div>
<aside id="det" class="card" aria-live="polite">Pilih pin pada peta untuk melihat detail makanan.</aside></div>
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const pins=@json($pins),partners=@json($partners),csrf='{{ csrf_token() }}',base='{{ url('/reports') }}';
let user={lat:-6.2088,lng:106.8456};
const map=L.map('map').setView([user.lat,user.lng],11);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(map);
const esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const km=(a,b,c,d)=>{const q=Math.PI/180,x=Math.sin((c-a)*q/2)**2+Math.cos(a*q)*Math.cos(c*q)*Math.sin((d-b)*q/2)**2;return 12742*Math.asin(Math.sqrt(x))};
const hms=m=>{m=Math.max(0,m)/1000|0;return [m/3600|0,m%3600/60|0,m%60].map(v=>String(v).padStart(2,'0')).join(':')};
const layer=L.layerGroup().addTo(map);let userM=null,route=null,sel=null;
partners.forEach(p=>L.marker([p.lat,p.lng],{icon:L.divIcon({html:p.type=='layak'?'🏠':'♻️',className:'',iconSize:[20,20]})}).bindTooltip(p.name).addTo(map));
function setUser(){userM&&userM.remove();userM=L.circleMarker([user.lat,user.lng],{radius:8,color:'#fff',weight:3,fillColor:'#2563eb',fillOpacity:1}).addTo(map).bindTooltip('Lokasi Anda');draw()}
navigator.geolocation&&navigator.geolocation.getCurrentPosition(p=>{user={lat:p.coords.latitude,lng:p.coords.longitude};setUser()},()=>{});
function list(){const q=$q.value.toLowerCase(),c=$c.value,r=+$r.value,u=$u.checked;return pins.filter(p=>(!c||p.category==c)&&(!u||p.urgent)&&km(user.lat,user.lng,p.lat,p.lng)<=r&&(p.title+p.provider).toLowerCase().includes(q))}
const $q=document.getElementById('q'),$c=document.getElementById('c'),$r=document.getElementById('r'),$u=document.getElementById('u');
function draw(){layer.clearLayers();list().forEach(p=>L.circleMarker([p.lat,p.lng],{radius:p.id==sel?15:11,color:'#fff',weight:3,fillColor:p.color,fillOpacity:1}).addTo(layer).on('click',()=>{sel=p.id;draw();show(p)}))}
function act(p,a,l,file){return `<form method="POST" action="${base}/${p.id}/${a}" ${file?'enctype="multipart/form-data"':''} class="mt-2">${file?'<input type="file" name="proof" accept="image/*" required class="mb-2 block text-sm" aria-label="Foto bukti">':''}<input type="hidden" name="_token" value="${csrf}"><button class="btn w-full">${l}</button></form>`}
function show(p){const d=km(user.lat,user.lng,p.lat,p.lng);route&&route.remove();route=null;if(p.status!='waiting')route=L.polyline([[user.lat,user.lng],[p.lat,p.lng]],{color:'#1d4a47',dashArray:'8 6'}).addTo(map);
document.getElementById('det').innerHTML=`<span class="rounded-full bg-slate-100 px-2 py-1 text-xs">${p.category=='layak'?'Human-First':'Earth-First'}</span>${p.urgent?' <span class="rounded-full bg-red-100 px-2 py-1 text-xs text-red-700">Segera kedaluwarsa</span>':''}
<h2 class="mt-2 text-lg font-bold">${esc(p.title)}</h2><p class="text-sm text-slate-500">${esc(p.provider)} · ${p.portions?p.portions+' porsi · ':''}${p.kg} kg<br>${d.toFixed(1).replace('.',',')} km dari Anda · ±${Math.max(3,Math.round(d/30*60))} menit</p>
<div class="my-3 rounded-lg bg-slate-100 p-3 text-center font-mono text-2xl" id="cd">${hms(new Date(p.expires)-Date.now())}</div>
<p class="text-sm">Status: <b>${esc(p.label)}</b>${p.rerouted?' (dialihkan otomatis)':''}<br>Tujuan: ${esc(p.partner||'-')}<br>Dampak potensial: ${p.co2e.toString().replace('.',',')} kg CO₂e</p>
${p.status=='waiting'?act(p,'claim','Klaim Makanan'):p.status=='claimed'?act(p,'start','Mulai Antar')+act(p,'complete','Selesaikan + Upload Bukti Foto',1):act(p,'complete','Selesaikan + Upload Bukti Foto',1)}
${p.category=='layak'&&p.status=='waiting'?act(p,'reroute','Simulasi Auto Re-route').replace('btn w-full','btn btn-o w-full'):''}
<a class="mt-2 block text-sm text-emerald-700 underline" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&origin=${user.lat},${user.lng}&destination=${p.lat},${p.lng}">Buka Google Maps</a>`;
clearInterval(show.t);show.t=setInterval(()=>{const e=document.getElementById('cd');e&&(e.textContent=hms(new Date(p.expires)-Date.now()))},1000)}
['input','change'].forEach(e=>[$q,$c,$r,$u].forEach(x=>x.addEventListener(e,draw)));setUser();
</script>@endpush
@endsection
