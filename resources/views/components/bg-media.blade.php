{{--
  Latar sebuah section: video → foto → gradasi kontur (dipilih otomatis sesuai file yang tersedia).
  Video : public/videos/{name}.webm dan/atau public/videos/{name}.mp4
  Poster: public/images/{name}.jpg (dipakai juga bila video tidak diputar)
  Aksesibilitas: video selalu bisu, tidak diputar bila pengguna memilih "kurangi animasi"
  (panel aksesibilitas / pengaturan sistem) atau mode hemat data, dan berhenti saat section tidak terlihat.
  Parent wajib berkelas "relative isolate".
--}}
@props([
    'name',
    'kenburns' => false,
    'focus' => 'center',
    'overlay' => null,
    'fallback' => 'bg-gradient-to-br from-[#0c1f19] via-[#143a2c] to-[#1f4b4a]',
])
@php
  $find = function (string ...$paths): ?string {
      foreach ($paths as $path) {
          if (file_exists(public_path($path))) {
              return asset($path);
          }
      }

      return null;
  };
  $webm = $find("videos/{$name}.webm", "video/{$name}.webm");
  $mp4 = $find("videos/{$name}.mp4", "video/{$name}.mp4");
  $poster = $find("images/{$name}.jpg", "images/{$name}.webp", "video/{$name}.jpg");
@endphp

@if($webm || $mp4)
 <div class="absolute inset-0 -z-10" aria-hidden="true" x-data="bgVideo($el.querySelector('video'))">
  <video class="h-full w-full object-cover" style="object-position: {{ $focus }}" muted loop playsinline preload="metadata" @if($poster) poster="{{ $poster }}" @endif disablepictureinpicture>
   @if($webm)<source src="{{ $webm }}" type="video/webm">@endif
   @if($mp4)<source src="{{ $mp4 }}" type="video/mp4">@endif
  </video>
  @if($overlay)<div class="absolute inset-0 {{ $overlay }}"></div>@endif
 </div>
@elseif($poster)
 <img src="{{ $poster }}" alt="" style="object-position: {{ $focus }}" class="absolute inset-0 -z-10 h-full w-full object-cover {{ $kenburns ? 'animate-kenburns' : '' }}">
 @if($overlay)<div class="absolute inset-0 -z-10 {{ $overlay }}"></div>@endif
@else
 <div class="absolute inset-0 -z-10 {{ $fallback }}"><x-contour /></div>
@endif

@once
@push('scripts')
<script>
/* Kendali video latar: hormati preferensi gerak & hemat data, jeda saat tidak terlihat */
function bgVideo(video){return{playing:false,userPaused:false,
 init(){if(!video)return;
  const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches||document.documentElement.classList.contains('rm')||navigator.connection?.saveData;
  this.userPaused=!!reduce;
  new IntersectionObserver(([e])=>{e.isIntersecting&&!this.userPaused&&!document.documentElement.classList.contains('rm')?this.play():video.pause()},{threshold:.1}).observe(video);
  video.addEventListener('play',()=>this.playing=true);video.addEventListener('pause',()=>this.playing=false)},
 play(){video.play().catch(()=>{this.playing=false})},
 toggle(){if(video.paused){this.userPaused=false;this.play()}else{this.userPaused=true;video.pause()}}}}
</script>
@endpush
@endonce
