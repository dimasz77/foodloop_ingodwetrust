@extends('layout')
@section('title','Peta')
@push('head')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>.leaflet-div-icon,.fl-pin{background:none;border:0}.fl-pin span{transition:transform .2s}.fl-pin:hover span.dot{transform:scale(1.25)}</style>@endpush
@section('content')
<x-page-hero image="peta.jpg" eyebrow="Peta Penyelamatan" title="Temukan Makanan di Sekitar Anda" subtitle="Klaim penjemputan, hubungi pemberi, dan salurkan sebelum batas waktu berakhir." :crumbs="['Peta' => null]">
 <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold ring-1 ring-white/20 backdrop-blur" role="status"><span class="h-2 w-2 animate-pulse rounded-full bg-lime-300"></span><span id="upd">Data langsung · diperbarui otomatis</span></span>
 @guest<a class="btn btn-lime" href="{{ route('login') }}">Masuk untuk klaim</a>@endguest
 @auth @unless(auth()->user()->isProvider())
  <button id="notif" type="button" class="btn btn-lime" aria-describedby="notif-hint"><svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7a4 4 0 0 1 8 0c0 3 1.5 4 1.5 4h-11S4 10 4 7zM6.5 13.5a1.5 1.5 0 0 0 3 0"/></svg><span>Aktifkan Notifikasi Darurat</span></button>
  <span id="notif-hint" class="text-xs text-white/70">Kabari saya saat ada makanan baru yang bisa saya klaim.</span>
 @endunless @endauth
</x-page-hero>

<section class="pull-up mx-auto max-w-6xl px-4">
 <div class="flex flex-wrap items-center gap-2 rounded-2xl bg-white p-2.5 shadow-xl ring-1 ring-black/5" role="search" aria-label="Filter makanan">
  <div class="relative min-w-[12rem] flex-1"><svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5"/></svg>
   <input id="q" type="search" class="inp pl-9 pr-10" placeholder="Cari jenis makanan atau penyedia…" aria-label="Cari makanan atau penyedia" aria-keyshortcuts="/"><kbd class="pointer-events-none absolute right-3 top-1/2 hidden -translate-y-1/2 sm:block" title="Tekan / untuk mencari">/</kbd></div>
  <select id="c" class="inp w-auto" aria-label="Kategori"><option value="">Semua kategori<option value="layak">Layak konsumsi<option value="organik">Organik</select>
  <select id="r" class="inp w-auto" aria-label="Radius jarak"><option value="">Semua jarak<option value="2">≤ 2 km<option value="5">≤ 5 km<option value="10">≤ 10 km<option value="25">≤ 25 km</select>
  <label class="flex cursor-pointer items-center gap-2 px-2 text-xs font-bold">Darurat saja <input type="checkbox" id="u" class="peer sr-only"><span class="h-5 w-9 rounded-full bg-slate-300 p-0.5 transition peer-checked:bg-red-600 peer-focus-visible:ring-2 peer-focus-visible:ring-red-300 [&>i]:peer-checked:translate-x-4"><i class="block h-4 w-4 rounded-full bg-white transition"></i></span></label>
  <button id="reset" type="button" hidden class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Reset filter</button>
 </div>

 <div class="mt-3 grid gap-3 md:grid-cols-[1fr_300px]">
  <div class="relative overflow-hidden rounded-2xl ring-1 ring-black/10">
   <div id="map" class="h-[420px] md:h-[480px]" role="application" aria-label="Peta makanan surplus"></div>
   <button id="me" type="button" class="absolute right-3 top-3 z-[500] rounded-lg bg-white px-3 py-2 text-xs font-bold shadow transition hover:bg-slate-50" aria-label="Pusatkan ke lokasi saya">🎯 Lokasi saya</button>
   <div class="absolute bottom-3 left-3 z-[500] rounded-lg bg-white/95 p-2 text-[0.6875rem] shadow"><b>LEGENDA</b><br><i class="mr-1 inline-block h-2 w-2 rounded-full bg-[#047857]"></i>Layak Konsumsi<br><i class="mr-1 inline-block h-2 w-2 rounded-full bg-[#f59e0b]"></i>Sisa Organik (Kompos)<br><i class="mr-1 inline-block h-2 w-2 rounded-full bg-[#dc2626]"></i>Segera Kedaluwarsa<br>🏠 Mitra penerima · ♻️ Pengolah organik</div>
  </div>
  <aside class="flex max-h-[480px] flex-col rounded-2xl bg-white shadow-sm ring-1 ring-black/5" aria-label="Daftar makanan">
   <div class="flex items-center justify-between border-b px-4 py-3"><b class="text-xs">Makanan tersedia</b><span id="cnt" class="chip bg-moss/10 text-moss">0</span></div>
   <ul id="list" class="flex-1 divide-y divide-slate-100 overflow-y-auto"></ul>
  </aside>
 </div>
 <div id="det" class="mt-6 scroll-mt-24" aria-live="polite"><div class="flex items-center gap-4 rounded-2xl border-2 border-dashed border-slate-200 p-6 text-sm text-slate-500"><svg class="h-8 w-8 shrink-0 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg><span><b class="block text-slate-700">Belum ada makanan dipilih</b>Tekan pin di peta atau item di daftar untuk melihat detail, batas waktu, dan tombol klaim.</span></div></div>
 <x-help class="mt-6" title="Cara mengklaim makanan">
  <p><b>1. Pilih</b> makanan di peta atau daftar. Warna merah berarti batas waktunya kurang dari 1 jam.</p>
  <p><b>2. Klaim</b>, lalu hubungi pemberi lewat tombol WhatsApp yang muncul setelah klaim berhasil.</p>
  <p><b>3. Jemput &amp; salurkan</b>, kemudian unggah foto bukti. Berubah pikiran? Anda bisa <b>membatalkan klaim</b> kapan saja sebelum selesai.</p>
  <p>Pintasan: tekan <kbd>/</kbd> untuk langsung mencari, <kbd>Esc</kbd> untuk mengosongkan pencarian.</p>
 </x-help>
</section>

<section class="relative isolate overflow-hidden bg-[#143a2c] py-16 text-white" data-reveal>
 <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#1f4b4a] to-[#0c1f19]"><x-contour /></div>
 <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-6 px-4">
  <div class="max-w-xl"><p class="sec !text-lime-300"><span class="h-[3px] w-8 rounded bg-lime-300"></span>Jaringan Relawan</p><h2 class="mt-3 text-3xl font-extrabold leading-tight">Relawan Terverifikasi. Penjemputan Lebih Cepat.</h2><p class="mt-3 text-sm text-white/75">Setiap laporan terhubung dengan relawan dan panti terdekat agar makanan sampai tepat waktu, aman, dan tepat sasaran.</p></div>
  @guest<a class="btn btn-lime px-6 py-3 text-sm" href="{{ route('register', ['role' => 'recipient']) }}">Daftar sebagai Penerima</a>@endguest
 </div>
</section>

@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let pins=@json($pins);
const partners=@json($partners),csrf='{{ csrf_token() }}',base='{{ url('/reports') }}',pinsUrl='{{ route('map.pins') }}';
const role=@json($role),loginUrl='{{ route('login') }}',focusId=@json($focus);
let user=@json($home),sel=null,routes=[],userM=null,tick=null,lastSig='';
const map=L.map('map',{zoomControl:false}).setView([user.lat,user.lng],11);
L.control.zoom({position:'bottomright'}).addTo(map);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(map);
const $=i=>document.getElementById(i),esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const km=(a,b,c,d)=>{const q=Math.PI/180,x=Math.sin((c-a)*q/2)**2+Math.cos(a*q)*Math.cos(c*q)*Math.sin((d-b)*q/2)**2;return 12742*Math.asin(Math.sqrt(x))};
const dist=k=>k<1?Math.round(k*1000)+' m':k.toFixed(1).replace('.',',')+' km';
const pad=n=>String(n).padStart(2,'0');
const statusChip={waiting:'bg-green-100 text-green-800',claimed:'bg-sky-100 text-sky-800',on_the_way:'bg-indigo-100 text-indigo-800'};
partners.forEach(p=>L.marker([p.lat,p.lng],{icon:L.divIcon({html:`<span class="grid h-7 w-7 place-items-center rounded-full bg-white text-sm shadow ring-1 ring-black/10">${p.type=='layak'?'🏠':'♻️'}</span>`,className:'fl-pin',iconSize:[28,28],iconAnchor:[14,14]})}).bindTooltip(esc(p.name)).addTo(map));
const layer=L.layerGroup().addTo(map);

function icon(p){const s=p.id==sel?28:20;
 return L.divIcon({className:'fl-pin',iconSize:[s,s],iconAnchor:[s/2,s/2],html:`<span class="relative block" style="width:${s}px;height:${s}px">${p.urgent?`<span class="absolute inset-0 animate-ring rounded-full" style="background:${p.color}"></span>`:''}<span class="dot relative block h-full w-full rounded-full border-[3px] border-white shadow-lg" style="background:${p.color}"></span></span>`})}
function filtered(){const q=$('q').value.toLowerCase(),c=$('c').value,u=$('u').checked,r=+$('r').value;
 return pins.map(p=>({...p,d:km(user.lat,user.lng,p.lat,p.lng)})).filter(p=>(!c||p.category==c)&&(!u||p.urgent)&&(!r||p.d<=r)&&(p.title+' '+p.provider).toLowerCase().includes(q)).sort((a,b)=>(b.urgent-a.urgent)||(a.d-b.d))}
function draw(){layer.clearLayers();const list=filtered();
 list.forEach(p=>L.marker([p.lat,p.lng],{icon:icon(p),zIndexOffset:p.id==sel?1000:(p.urgent?500:0),keyboard:true,title:p.title}).addTo(layer).bindTooltip(`<b>${esc(p.title)}</b><br>${dist(p.d)}`).on('click',()=>pick(p.id)));
 $('cnt').textContent=list.length;
 $('list').innerHTML=list.length?list.map(p=>`<li><button type="button" data-id="${p.id}" class="flex w-full gap-3 px-4 py-3 text-left text-xs transition hover:bg-slate-50 ${p.id==sel?'bg-moss/5 ring-2 ring-inset ring-moss/30':''}">
  <span class="mt-1 h-3 w-3 shrink-0 rounded-full ${p.urgent?'animate-pulse':''}" style="background:${p.color}"></span>
  <span class="min-w-0 flex-1"><b class="block truncate">${esc(p.title)}</b><span class="text-slate-500">${esc(p.provider)} · ${dist(p.d)}</span><span class="mt-1 flex flex-wrap items-center gap-1"><span class="chip ${statusChip[p.status]||'bg-slate-100'}">${esc(p.label)}</span><span class="font-mono text-[0.6875rem] ${p.urgent?'font-bold text-red-600':'text-slate-400'}" data-exp="${p.expires}">--:--:--</span></span></span></button></li>`).join('')
  :'<li class="p-6 text-center text-xs text-slate-500">🔍 Tidak ada makanan yang cocok dengan filter.<br>Coba perluas radius atau hapus filter.</li>';
 $('list').querySelectorAll('[data-id]').forEach(b=>b.addEventListener('click',()=>pick(+b.dataset.id,true)))}

function setUser(){userM&&userM.remove();userM=L.marker([user.lat,user.lng],{icon:L.divIcon({className:'fl-pin',iconSize:[18,18],iconAnchor:[9,9],html:'<span class="relative block h-[18px] w-[18px]"><span class="absolute inset-0 animate-ring rounded-full bg-blue-500"></span><span class="relative block h-full w-full rounded-full border-[3px] border-white bg-blue-600 shadow"></span></span>'})}).addTo(map).bindTooltip('Lokasi Anda');draw()}
function locate(fly){navigator.geolocation&&navigator.geolocation.getCurrentPosition(p=>{user={lat:p.coords.latitude,lng:p.coords.longitude};setUser();fly&&map.flyTo([user.lat,user.lng],13)},()=>{fly&&map.flyTo([user.lat,user.lng],12)})}
$('me').addEventListener('click',()=>locate(true));

function act(p,a,l,cls=''){return `<form method="POST" action="${base}/${p.id}/${a}" class="mt-2"><input type="hidden" name="_token" value="${csrf}"><button class="btn w-full ${cls}">${l}</button></form>`}
function actions(p){
 if(!role)return `<a class="btn btn-g mt-3 w-full py-3" href="${loginUrl}">Masuk untuk mengklaim</a>`;
 let h='';
 if(role!='provider'){
  if(p.status=='waiting'&&p.canClaim)h+=act(p,'claim',p.category=='layak'?'Klaim Makanan Ini':'Klaim untuk Diolah','btn-g py-3 text-sm');
  else if(p.status=='waiting')h+=`<p class="mt-2 rounded-lg bg-amber-50 p-2 text-xs text-amber-900">${p.category=='layak'?'Makanan layak konsumsi hanya dapat diklaim oleh <b>Penerima Makanan</b>.':'Sisa organik hanya dapat diklaim oleh <b>Mitra Pengolah Organik</b> (BSF/kompos).'}</p>`;
  else if(p.claimedByMe){
   if(p.contact)h+=`<a class="btn mt-2 w-full bg-[#25D366] hover:bg-[#1da851]" target="_blank" rel="noopener" href="${esc(p.contact)}">💬 Hubungi Pemberi via WhatsApp</a>`;
   if(p.status=='claimed')h+=act(p,'start','🚚 Mulai Antar');
   h+=`<form method="POST" action="${base}/${p.id}/release" class="mt-2" onsubmit="return confirmRelease(this)"><input type="hidden" name="_token" value="${csrf}"><button class="w-full text-center text-[0.75rem] font-bold text-red-600 hover:underline">Batalkan klaim</button></form>`;
  } else h+='<p class="mt-2 rounded-lg bg-slate-50 p-2 text-xs text-slate-500">Sedang diproses relawan lain.</p>';
 } else {
  h+='<p class="mt-2 rounded-lg bg-slate-50 p-2 text-xs text-slate-500">Anda masuk sebagai pemberi makanan. Hanya penerima yang dapat mengklaim.</p>';
  if(p.mine&&p.category=='layak'&&p.status=='waiting')h+=act(p,'reroute','Simulasi Auto Re-route','btn-w');
 }
 return h}
window.confirmRelease=f=>{if(f.dataset.ok)return true;const b=f.querySelector('button');b.textContent='Tekan sekali lagi untuk membatalkan klaim';b.classList.add('rounded-lg','bg-red-50','py-2');f.dataset.ok=1;return false};

function proof(p){
 if(!(p.claimedByMe&&p.status!='waiting'))return '<p class="mt-2 text-xs text-slate-500">Hanya penerima yang mengklaim makanan ini yang dapat mengunggah bukti.</p>';
 return `<form method="POST" action="${base}/${p.id}/complete" enctype="multipart/form-data" class="card mt-2 grid gap-3 border-dashed sm:grid-cols-2" x-data="{src:null}"><input type="hidden" name="_token" value="${csrf}">
  <label class="grid h-36 cursor-pointer place-items-center overflow-hidden rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 text-center text-xs text-slate-500 hover:border-moss"><template x-if="src"><img :src="src" alt="Pratinjau bukti" class="h-full w-full object-cover"></template><span x-show="!src">📷<br><b>Pilih foto bukti</b></span>
   <input type="file" name="proof" accept="image/*" required class="sr-only" @change="src=$event.target.files[0]?URL.createObjectURL($event.target.files[0]):null"></label>
  <div><label class="lbl" for="note${p.id}">Ucapan terima kasih (opsional)</label><textarea id="note${p.id}" name="note" maxlength="200" rows="3" class="inp" placeholder="Terima kasih, makanannya sudah sampai di panti!"></textarea><button class="btn btn-g mt-2 w-full">✅ Selesaikan Penyaluran</button></div></form>`}

function pick(id,fly){sel=id;const p=pins.find(x=>x.id==id);if(!p)return;draw();
 routes.forEach(r=>r.remove());routes=[L.polyline([[user.lat,user.lng],[p.lat,p.lng]],{color:'#1f4b4a',weight:3,dashArray:'8 6'}).addTo(map)];
 const dest=partners.find(x=>x.name==p.partner);dest&&routes.push(L.polyline([[p.lat,p.lng],[dest.lat,dest.lng]],{color:p.color,weight:2,dashArray:'2 6',opacity:.8}).addTo(map));
 fly?map.flyTo([p.lat,p.lng],Math.max(map.getZoom(),13),{duration:.6}):null;
 const d=km(user.lat,user.lng,p.lat,p.lng);lastSig=p.status+p.claimedByMe;
 $('det').innerHTML=`<div class="card animate-pop grid gap-5 md:grid-cols-2">
 ${p.photo?`<img src="${esc(p.photo)}" alt="Foto ${esc(p.title)}" class="h-64 w-full rounded-xl object-cover">`:`<div class="grid h-64 place-items-center rounded-xl bg-gradient-to-br ${p.category=='layak'?'from-amber-100 to-orange-200':'from-lime-100 to-amber-100'} text-8xl" role="img" aria-label="Ilustrasi makanan">${p.category=='layak'?'🍱':'🌿'}</div>`}
 <div><div class="flex flex-wrap gap-1">${p.urgent?'<span class="chip bg-red-100 text-red-700">⚡ Segera kedaluwarsa</span>':''}${p.rerouted?'<span class="chip bg-amber-100 text-amber-800">🔁 Dialihkan otomatis</span>':''}<span class="chip ${p.category=='layak'?'bg-green-100 text-green-800':'bg-amber-100 text-amber-800'}">${p.category=='layak'?'Human-First':'Earth-First'}</span></div>
 <h2 class="mt-2 text-lg font-extrabold">${esc(p.title)}</h2>
 <div class="mt-2 grid grid-cols-3 gap-2 text-center text-xs"><div class="rounded-lg bg-slate-50 p-2"><b class="block text-base">${p.portions||'-'}</b>porsi</div><div class="rounded-lg bg-slate-50 p-2"><b class="block text-base">${String(p.kg).replace('.',',')}</b>kg</div><div class="rounded-lg bg-slate-50 p-2"><b class="block text-base">${String(p.co2e).replace('.',',')}</b>kg CO₂e</div></div>
 <p class="mt-3 text-xs text-slate-600">🏪 ${esc(p.provider)}<br>📍 ${dist(d)} dari lokasi Anda<br>🎯 Tujuan: ${esc(p.partner||'-')}</p>
 ${p.description?`<p class="mt-2 whitespace-pre-line rounded-lg bg-slate-50 p-2 text-[0.75rem] text-slate-600">${esc(p.description)}</p>`:''}
 <div class="mt-3 rounded-xl ${p.urgent?'bg-red-50':'bg-green-50'} p-3 text-center"><div class="text-[0.6875rem] font-bold ${p.urgent?'text-red-700':'text-moss'}">⏱ Batas waktu pengambilan</div><div class="font-mono text-2xl font-extrabold"><span id="ch">00</span> : <span id="cm">00</span> : <span id="cs">00</span></div><div class="flex justify-around text-[0.625rem] text-slate-500"><span>Jam</span><span>Menit</span><span>Detik</span></div></div>
 <p class="mt-2 text-xs">Status: <span class="chip ${statusChip[p.status]||'bg-slate-100'}">${esc(p.label)}</span></p>
 ${actions(p)}</div></div>
 <div class="mt-5 flex flex-wrap items-center justify-between gap-3"><div><h3 class="font-extrabold">Navigasi</h3><p class="text-xs text-slate-500">Ikuti rute terbaik untuk mengambil makanan sebelum batas waktu berakhir.</p></div><a class="btn btn-w" target="_blank" rel="noopener" href="https://www.google.com/maps/dir/?api=1&origin=${user.lat},${user.lng}&destination=${p.lat},${p.lng}">Buka Google Maps ↗</a></div>
 <div class="mt-5"><h3 class="font-extrabold">Bukti Penyaluran</h3><p class="text-xs text-slate-500">Unggah foto bukti serah terima dari lokasi Anda.</p>${proof(p)}</div>`;
 const e=new Date(p.expires);clearInterval(tick);const t=()=>{let s=Math.max(0,(e-Date.now())/1000|0);$('ch')&&($('ch').textContent=pad(s/3600|0),$('cm').textContent=pad(s%3600/60|0),$('cs').textContent=pad(s%60))};t();tick=setInterval(t,1000);
 fly&&$('det').scrollIntoView({behavior:'smooth',block:'start'})}

/* Pembaruan berkala tanpa memuat ulang halaman */
/* Notifikasi darurat: beri tahu saat muncul makanan baru (atau menjadi darurat) yang bisa diklaim pengguna ini */
const notifKey='fl-notif',claimable=p=>p.canClaim&&p.status=='waiting';
let known=new Map(pins.map(p=>[p.id,p.urgent]));
const notifOn=()=>{try{return 'Notification' in window&&Notification.permission==='granted'&&localStorage.getItem(notifKey)==='1'}catch(e){return false}};
function syncNotifBtn(){const b=$('notif');if(!b)return;const on=notifOn();b.querySelector('span').textContent=on?'Notifikasi Aktif · Matikan':'Aktifkan Notifikasi Darurat';b.setAttribute('aria-pressed',on);b.classList.toggle('btn-lime',!on);b.classList.toggle('btn-w',on)}
$('notif')?.addEventListener('click',async()=>{
 if(!('Notification' in window)){$('notif-hint').textContent='Browser ini belum mendukung notifikasi.';return}
 if(notifOn()){localStorage.setItem(notifKey,'0');syncNotifBtn();return}
 const perm=await Notification.requestPermission();
 if(perm==='granted'){localStorage.setItem(notifKey,'1');new Notification('Notifikasi FoodLoop aktif',{body:'Kami akan memberi tahu saat ada makanan baru yang bisa Anda klaim.',icon:'{{ asset('icons/icon-192.png') }}'})}
 else $('notif-hint').textContent='Izin notifikasi ditolak. Aktifkan lewat pengaturan situs di browser.';
 syncNotifBtn()});
function notifyNew(){
 const fresh=pins.filter(p=>claimable(p)&&(!known.has(p.id)||(p.urgent&&!known.get(p.id))));
 known=new Map(pins.map(p=>[p.id,p.urgent]));
 if(!notifOn())return;
 fresh.slice(0,3).forEach(p=>{const n=new Notification((p.urgent?'⏱ Darurat: ':'Baru: ')+p.title,{body:`${p.provider} · ${dist(km(user.lat,user.lng,p.lat,p.lng))} · ${p.portions?p.portions+' porsi':p.kg+' kg'}`,icon:'{{ asset('icons/icon-192.png') }}',tag:'fl-'+p.id});n.onclick=()=>{window.focus();pick(p.id,true);n.close()}});
}
async function refresh(){try{const r=await fetch(pinsUrl,{headers:{Accept:'application/json'}});if(!r.ok)return;const j=await r.json();pins=j.pins;notifyNew();
 $('upd').textContent='Diperbarui '+new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
 const p=pins.find(x=>x.id==sel);
 if(sel&&!p){sel=null;$('det').innerHTML='<div class="card text-xs text-slate-500">Makanan yang Anda lihat sudah tidak tersedia.</div>';routes.forEach(r=>r.remove())}
 else if(p&&p.status+p.claimedByMe!==lastSig&&!$('det').contains(document.activeElement))pick(sel);
 draw()}catch(e){}}
/* Tab aktif: tiap 30 detik. Tab di latar belakang: tiap 60 detik hanya jika notifikasi aktif. */
let ticks=0;setInterval(()=>{ticks++;if(document.visibilityState==='visible'||(notifOn()&&ticks%2===0))refresh()},30000);

/* Reset filter (kontrol pengguna) & pintasan keyboard (efisiensi) */
const syncReset=()=>{$('reset').hidden=!($('q').value||$('c').value||$('r').value||$('u').checked)};
['input','change'].forEach(e=>['q','c','u','r'].forEach(i=>$(i).addEventListener(e,()=>{draw();syncReset()})));
$('reset').addEventListener('click',()=>{$('q').value='';$('c').value='';$('r').value='';$('u').checked=false;draw();syncReset();$('q').focus()});
document.addEventListener('keydown',e=>{const t=e.target.tagName;if(e.key=='/'&&!['INPUT','TEXTAREA','SELECT'].includes(t)){e.preventDefault();$('q').focus()}if(e.key=='Escape'&&e.target.id=='q'){$('q').value='';draw();syncReset()}});
if(role=='processor'){$('c').value='organik';syncReset()}
syncNotifBtn();setUser();locate(false);
focusId&&pins.some(p=>p.id==focusId)&&pick(focusId,true);
</script>@endpush
@endsection
