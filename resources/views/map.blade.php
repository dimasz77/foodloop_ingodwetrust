@extends('site')
@section('title','Peta')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"><link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">@endpush
@section('content')
<div class="grid md:h-[calc(100vh-57px)] md:grid-cols-[400px_1fr]">
 <section class="order-last flex min-h-0 flex-col bg-gradient-to-b from-lime-200 to-[#f6f4ec] md:order-none">
  <div class="space-y-3 p-4">
   <div class="flex items-center justify-between"><div><div class="text-sm font-bold underline">📍 Lokasi Anda</div><div class="text-xs text-slate-600">Dalam <select id="r" class="rounded border-0 bg-transparent p-0 text-xs font-bold" aria-label="Radius"><option value="999">semua jarak<option value="5" selected>5 km<option value="10">10 km</select></div></div></div>
   <div class="flex gap-2 overflow-x-auto" id="chips" role="group" aria-label="Filter kategori">@foreach([''=>'Semua','layak'=>'🍱 Layak','organik'=>'🌿 Organik','darurat'=>'⚠️ Darurat'] as $k=>$l)<button type="button" data-c="{{ $k }}" class="whitespace-nowrap rounded-full bg-white px-4 py-2 text-sm font-bold shadow-sm">{{ $l }}</button>@endforeach</div>
   <input id="q" type="search" class="inp rounded-full" placeholder="Cari makanan atau penyedia…" aria-label="Cari">
  </div>
  <h1 class="px-4 text-2xl font-extrabold">Tangkap! 🍱</h1>
  <div id="list" class="min-h-0 flex-1 space-y-3 overflow-y-auto p-4 md:max-h-none max-h-[480px]" aria-live="polite"></div>
 </section>
 <section class="relative h-[380px] md:h-auto"><div id="map" class="absolute inset-0" role="application" aria-label="Peta makanan surplus"></div>
  <div id="sheet" class="absolute inset-x-3 bottom-3 z-[500] hidden max-h-[70%] overflow-y-auto rounded-2xl bg-white p-4 shadow-xl md:inset-x-auto md:left-3 md:w-[380px]" aria-live="polite"></div></section>
</div>
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
const pins=@json($pins),partners=@json($partners),csrf='{{ csrf_token() }}',base='{{ url('/reports') }}';
let user={lat:-6.2088,lng:106.8456},cat='',sel=null,route=null,userM=null,tick=null;
const map=L.map('map').setView([user.lat,user.lng],11);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(map);
const cl=L.markerClusterGroup({iconCreateFunction:c=>L.divIcon({html:`<div class="grid h-11 w-11 place-items-center rounded-full bg-lime-300 font-extrabold text-green-900 ring-4 ring-lime-200">${c.getChildCount()}</div>`,className:'',iconSize:[44,44]})}).addTo(map);
const $=i=>document.getElementById(i),esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const km=(a,b,c,d)=>{const q=Math.PI/180,x=Math.sin((c-a)*q/2)**2+Math.cos(a*q)*Math.cos(c*q)*Math.sin((d-b)*q/2)**2;return 12742*Math.asin(Math.sqrt(x))};
const hms=m=>{m=Math.max(0,m)/1000|0;return [m/3600|0,m%3600/60|0,m%60].map(v=>String(v).padStart(2,'0')).join(':')};
const hm=p=>new Date(p.expires).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
partners.forEach(p=>L.marker([p.lat,p.lng],{icon:L.divIcon({html:p.type=='layak'?'🏠':'♻️',className:'',iconSize:[20,20]})}).bindTooltip(p.name).addTo(map));
function setUser(){userM&&userM.remove();userM=L.circleMarker([user.lat,user.lng],{radius:8,color:'#fff',weight:3,fillColor:'#2563eb',fillOpacity:1}).addTo(map).bindTooltip('Lokasi Anda');render()}
navigator.geolocation&&navigator.geolocation.getCurrentPosition(p=>{user={lat:p.coords.latitude,lng:p.coords.longitude};map.setView([user.lat,user.lng],12);setUser()},()=>{});
function filtered(){const q=$('q').value.toLowerCase(),r=+$('r').value;return pins.filter(p=>(!cat||(cat=='darurat'?p.urgent:p.category==cat))&&km(user.lat,user.lng,p.lat,p.lng)<=r&&(p.title+p.provider).toLowerCase().includes(q))}
function render(){const L2=filtered();cl.clearLayers();
 L2.forEach(p=>cl.addLayer(L.marker([p.lat,p.lng],{icon:L.divIcon({html:`<div style="background:${p.color}" class="h-5 w-5 rounded-full border-[3px] border-white shadow ${p.id==sel?'scale-150':''}"></div>`,className:'',iconSize:[20,20]})}).on('click',()=>pick(p.id))));
 $('list').innerHTML=L2.length?L2.map(p=>`<button data-id="${p.id}" class="block w-full overflow-hidden rounded-2xl bg-white text-left shadow-sm ring-1 ring-black/5 hover:ring-green-600 ${p.id==sel?'ring-2 ring-green-700':''}">
 <div class="relative grid h-24 place-items-center bg-gradient-to-br from-lime-200 to-green-400 text-5xl">${p.category=='layak'?'🍱':'🌿'}<span class="absolute left-3 top-3 rounded bg-yellow-200 px-2 py-0.5 text-[11px] font-extrabold">${p.portions?p.portions+' PORSI':p.kg+' KG'}</span>${p.urgent?'<span class="absolute right-3 top-3 rounded bg-red-100 px-2 py-0.5 text-[11px] font-extrabold text-red-700">MENDESAK</span>':''}${p.rerouted?'<span class="absolute right-3 top-3 rounded bg-amber-100 px-2 py-0.5 text-[11px] font-extrabold text-amber-800">DIALIHKAN</span>':''}</div>
 <div class="p-3"><b>${esc(p.title)}</b><div class="text-sm text-slate-500">${esc(p.provider)}</div><div class="mt-1 text-sm">📍 ${km(user.lat,user.lng,p.lat,p.lng).toFixed(1).replace('.',',')} km · ⏱ Hingga ${hm(p)} · ${esc(p.label)}</div></div></button>`).join(''):'<p class="card text-sm">Tidak ada makanan yang cocok dengan filter.</p>'}
function act(p,a,l,file,o){return `<form method="POST" action="${base}/${p.id}/${a}" ${file?'enctype="multipart/form-data"':''} class="mt-2"><input type="hidden" name="_token" value="${csrf}">${file?'<input type="file" name="proof" accept="image/*" required class="mb-2 block text-sm" aria-label="Foto bukti">':''}<button class="btn w-full ${o?'btn-o':''}">${l}</button></form>`}
function pick(id){sel=id;const p=pins.find(x=>x.id==id);render();map.flyTo([p.lat,p.lng],14);route&&route.remove();route=p.status!='waiting'?L.polyline([[user.lat,user.lng],[p.lat,p.lng]],{color:'#14401f',dashArray:'8 6'}).addTo(map):null;
 const d=km(user.lat,user.lng,p.lat,p.lng);$('sheet').classList.remove('hidden');
 $('sheet').innerHTML=`<button id="x" class="float-right text-xl" aria-label="Tutup">×</button><span class="rounded-full bg-lime-200 px-2 py-1 text-xs font-bold">${p.category=='layak'?'Human-First':'Earth-First'}</span>
 <h2 class="mt-2 text-lg font-extrabold">${esc(p.title)}</h2><p class="text-sm text-slate-500">${esc(p.provider)} · ${p.portions?p.portions+' porsi · ':''}${p.kg} kg · ${d.toFixed(1).replace('.',',')} km · ±${Math.max(3,Math.round(d/30*60))} menit</p>
 <div id="cd" class="my-3 rounded-xl bg-slate-100 p-3 text-center font-mono text-2xl">${hms(new Date(p.expires)-Date.now())}</div>
 <p class="text-sm">Status: <b>${esc(p.label)}</b>${p.rerouted?' (dialihkan otomatis)':''}<br>Tujuan: ${esc(p.partner||'-')}<br>Dampak potensial: ${String(p.co2e).replace('.',',')} kg CO₂e</p>
 ${p.status=='waiting'?act(p,'claim','Klaim Makanan'):(p.status=='claimed'?act(p,'start','Mulai Antar'):'')+(p.status!='waiting'?act(p,'complete','Selesaikan + Upload Bukti Foto',1):'')}
 ${p.category=='layak'&&p.status=='waiting'?act(p,'reroute','Simulasi Auto Re-route',0,1):''}
 <a class="mt-2 block text-sm text-green-800 underline" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&origin=${user.lat},${user.lng}&destination=${p.lat},${p.lng}">Buka Google Maps</a>`;
 $('x').onclick=()=>{$('sheet').classList.add('hidden');sel=null;route&&route.remove();render()};clearInterval(tick);tick=setInterval(()=>{const e=$('cd');e&&(e.textContent=hms(new Date(p.expires)-Date.now()))},1000)}
$('list').addEventListener('click',e=>{const b=e.target.closest('[data-id]');b&&pick(+b.dataset.id)});
$('chips').addEventListener('click',e=>{const b=e.target.closest('[data-c]');if(!b)return;cat=b.dataset.c;document.querySelectorAll('#chips button').forEach(x=>x.className=x.className.replace(/ ?!?(bg-lime-300|bg-white)/g,'')+(x==b?' bg-lime-300':' bg-white'));render()});
$('q').addEventListener('input',render);$('r').addEventListener('change',render);
document.querySelector('#chips button').click();setUser();
</script>@endpush
@endsection
