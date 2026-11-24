@extends('layout')
@section('title','Peta')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@section('content')
<section class="mx-auto max-w-5xl px-4 pt-8"><h1 class="h2">Interactive Rescue Map</h1><p class="mt-1 text-xs text-slate-600">Temukan makanan surplus di sekitar Anda, klaim penjemputan, dan bantu menyalurkannya kepada yang membutuhkan secara cepat dan tepat.</p>
 <div class="relative mt-4 overflow-hidden rounded-2xl ring-1 ring-black/10"><div id="map" class="h-[380px]" role="application" aria-label="Peta makanan surplus"></div>
  <div class="absolute left-3 right-3 top-3 z-[500] flex flex-wrap gap-2 rounded-xl bg-white/95 p-2 shadow"><input id="q" type="search" class="inp max-w-xs flex-1" placeholder="Cari lokasi atau jenis makanan…" aria-label="Cari">
   <select id="c" class="inp w-32" aria-label="Kategori"><option value="">Kategori<option value="layak">Layak Konsumsi<option value="organik">Organik</select>
   <label class="flex items-center gap-2 text-xs font-bold">Darurat <input type="checkbox" id="u" class="peer sr-only"><span class="h-5 w-9 rounded-full bg-slate-300 p-0.5 transition peer-checked:bg-red-600 [&>i]:peer-checked:translate-x-4"><i class="block h-4 w-4 rounded-full bg-white transition"></i></span></label></div>
  <div class="absolute bottom-3 left-3 z-[500] rounded-lg bg-white/95 p-2 text-[10px] shadow"><b>MAP LEGEND</b><br><i class="mr-1 inline-block h-2 w-2 rounded-full bg-[#1f6b3a]"></i>Masih Layak Konsumsi<br><i class="mr-1 inline-block h-2 w-2 rounded-full bg-[#f28c28]"></i>Sisa Organik (Kompos)<br><i class="mr-1 inline-block h-2 w-2 rounded-full bg-[#d9363e]"></i>Segera Kedaluwarsa</div></div>
 <p class="mt-2 text-xs text-slate-500">Tekan lokasi untuk melihat detail makanan</p>
 <div id="det" class="mt-4" aria-live="polite"></div></section>
<section class="mx-auto mt-12 grid max-w-5xl items-center gap-6 px-4 md:grid-cols-[2fr_1fr]"><div><h2 class="h2">Active Volunteer Network</h2><p class="mt-2 text-xs text-slate-600">FoodLoop menghubungkan laporan makanan dengan relawan terdekat yang telah terverifikasi, sehingga penjemputan dapat dilakukan lebih cepat, aman, dan tepat sasaran.</p><p class="mt-3 text-xs font-bold">Ingin berkontribusi menjadi relawan?</p><a class="btn mt-2" href="{{ route('register') }}">Join as Partner</a></div><div class="mx-auto grid h-36 w-36 place-items-center rounded-full bg-slate-800 text-6xl" role="img" aria-label="Relawan">🚚</div></section>
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const pins=@json($pins),partners=@json($partners),csrf='{{ csrf_token() }}',base='{{ url('/reports') }}';
const role=@json($role),loginUrl='{{ route('login') }}';
let user={lat:-6.2088,lng:106.8456},sel=null,route=null,userM=null,tick=null;
const map=L.map('map').setView([user.lat,user.lng],11);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(map);
const $=i=>document.getElementById(i),esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const km=(a,b,c,d)=>{const q=Math.PI/180,x=Math.sin((c-a)*q/2)**2+Math.cos(a*q)*Math.cos(c*q)*Math.sin((d-b)*q/2)**2;return 12742*Math.asin(Math.sqrt(x))};
const dist=k=>k<1?Math.round(k*1000)+' m':k.toFixed(1).replace('.',',')+' km';
const pad=n=>String(n).padStart(2,'0');
partners.forEach(p=>L.marker([p.lat,p.lng],{icon:L.divIcon({html:p.type=='layak'?'🏠':'♻️',className:'',iconSize:[20,20]})}).bindTooltip(p.name).addTo(map));
const layer=L.layerGroup().addTo(map);
function setUser(){userM&&userM.remove();userM=L.circleMarker([user.lat,user.lng],{radius:8,color:'#fff',weight:3,fillColor:'#2563eb',fillOpacity:1}).addTo(map).bindTooltip('Lokasi Anda');draw()}
navigator.geolocation&&navigator.geolocation.getCurrentPosition(p=>{user={lat:p.coords.latitude,lng:p.coords.longitude};setUser()},()=>{});
function draw(){layer.clearLayers();const q=$('q').value.toLowerCase(),c=$('c').value,u=$('u').checked;
 pins.filter(p=>(!c||p.category==c)&&(!u||p.urgent)&&(p.title+p.provider).toLowerCase().includes(q)).forEach(p=>L.circleMarker([p.lat,p.lng],{radius:p.id==sel?15:11,color:'#fff',weight:3,fillColor:p.color,fillOpacity:1}).addTo(layer).on('click',()=>pick(p.id)))}
function act(p,a,l,file,o){return `<form method="POST" action="${base}/${p.id}/${a}" ${file?'enctype="multipart/form-data"':''} class="mt-2"><input type="hidden" name="_token" value="${csrf}">${file?'<input type="file" name="proof" accept="image/*" required class="mb-2 block text-xs" aria-label="Foto bukti">':''}<button class="btn w-full ${o?'btn-w':''}">${l}</button></form>`}
function actions(p){
 if(!role)return `<a class="btn mt-2 w-full" href="${loginUrl}">Masuk sebagai penerima untuk klaim</a>`;
 let h='';
 if(role=='recipient'){if(p.status=='waiting')h+=act(p,'claim','Klaim Makanan');else if(p.claimedByMe&&p.status=='claimed')h+=act(p,'start','Mulai Antar');else if(!p.claimedByMe)h+='<p class="mt-2 text-xs text-slate-500">Sedang diproses relawan lain.</p>'}
 else{h+='<p class="mt-2 text-xs text-slate-500">Anda masuk sebagai pemberi makanan. Hanya penerima yang dapat mengklaim.</p>';if(p.mine&&p.category=='layak'&&p.status=='waiting')h+=act(p,'reroute','Simulasi Auto Re-route',0,1)}
 return h}
function pick(id){sel=id;const p=pins.find(x=>x.id==id);draw();route&&route.remove();route=p.status!='waiting'?L.polyline([[user.lat,user.lng],[p.lat,p.lng]],{color:'#1f4b4a',dashArray:'8 6'}).addTo(map):null;
 const d=km(user.lat,user.lng,p.lat,p.lng);
 $('det').innerHTML=`<div class="card grid gap-5 md:grid-cols-2"><div class="grid h-56 place-items-center rounded-xl bg-gradient-to-br from-amber-100 to-orange-200 text-8xl" role="img" aria-label="Foto makanan">${p.category=='layak'?'🍱':'🌿'}</div>
 <div>${p.urgent?'<span class="float-right rounded bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700">Segera kedaluwarsa</span>':''}${p.rerouted?'<span class="float-right rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">Dialihkan otomatis</span>':''}
 <h2 class="text-sm font-extrabold">${esc(p.title)}</h2><p class="text-xs text-slate-500">${p.portions?p.portions+' porsi ':''}(${p.kg} kg)<br>${esc(p.provider)}<br>📍 ${dist(d)} dari lokasi saat ini · tujuan: ${esc(p.partner||'-')}</p>
 <div class="mt-3 rounded-xl bg-red-50 p-3 text-center"><div class="text-[10px] font-bold text-red-700">⏱ Batas waktu pengambilan</div><div class="font-mono text-2xl font-extrabold"><span id="ch">00</span> : <span id="cm">00</span> : <span id="cs">00</span></div><div class="flex justify-around text-[9px] text-slate-500"><span>Jam</span><span>Menit</span><span>Detik</span></div></div>
 <p class="mt-2 text-xs">Status: <b>${esc(p.label)}</b></p>
 ${actions(p)}</div></div>
 <div class="mt-5 flex flex-wrap items-center justify-between gap-3"><div><h3 class="font-extrabold">Open StreetMap</h3><p class="text-xs text-slate-500">Ikuti rute terbaik untuk mengambil makanan sebelum batas waktu berakhir.</p></div><a class="btn btn-w" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&origin=${user.lat},${user.lng}&destination=${p.lat},${p.lng}">Buka Google Maps ↗</a></div>
 <div class="mt-5"><h3 class="font-extrabold">Proof of Delivery</h3><p class="text-xs text-slate-500">Upload foto bukti dari lokasi Anda</p>${p.claimedByMe&&p.status!='waiting'?`<div class="card mt-2 max-w-sm border-dashed text-center">${act(p,'complete','Selesaikan + Upload Bukti Foto',1)}</div>`:'<p class="mt-2 text-xs text-slate-500">Hanya penerima yang mengklaim makanan ini yang dapat mengunggah bukti.</p>'}</div>`;
 const e=new Date(p.expires);clearInterval(tick);const t=()=>{let s=Math.max(0,(e-Date.now())/1000|0);$('ch')&&($('ch').textContent=pad(s/3600|0),$('cm').textContent=pad(s%3600/60|0),$('cs').textContent=pad(s%60))};t();tick=setInterval(t,1000);
 $('det').scrollIntoView({behavior:'smooth',block:'nearest'})}
['input','change'].forEach(e=>['q','c','u'].forEach(i=>$(i).addEventListener(e,draw)));setUser();
</script>@endpush
@endsection
