{{-- Foto profil bulat dengan tombol pensil + pratinjau sebelum disimpan (maks. 2 MB). --}}
@props(['src' => null, 'initial' => null, 'name' => 'avatar'])
<div class="flex flex-col items-center" x-data="{preview:null,err:''}">
 <div class="relative">
  <div class="h-40 w-40 overflow-hidden rounded-full bg-gradient-to-br from-[#143a2c] to-[#1f4b4a] shadow-lg ring-4 ring-white">
   <template x-if="preview"><img :src="preview" alt="Pratinjau foto profil" class="h-full w-full object-cover"></template>
   @if($src)
    <img x-show="!preview" src="{{ $src }}" alt="Foto profil" class="h-full w-full object-cover">
   @elseif($initial)
    <span x-show="!preview" class="grid h-full w-full place-items-center text-5xl font-extrabold text-lime-300">{{ $initial }}</span>
   @else
    <svg x-show="!preview" class="h-full w-full p-9 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="8.5" r="4"/><path d="M4.5 20a7.5 7.5 0 0 1 15 0"/></svg>
   @endif
  </div>
  <label class="absolute bottom-0 right-0 grid h-14 w-14 cursor-pointer place-items-center rounded-full bg-[#173a39] text-white shadow-lg ring-4 ring-cream transition hover:bg-moss has-[:focus-visible]:outline has-[:focus-visible]:outline-[3px] has-[:focus-visible]:outline-lime-400" title="Pilih foto profil">
   <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20h4L19 9a2.8 2.8 0 0 0-4-4L4 16v4z"/><path d="M13.5 6.5l4 4"/></svg>
   <span class="sr-only">Pilih foto profil</span>
   <input type="file" name="{{ $name }}" accept="image/png,image/jpeg,image/webp" class="sr-only"
    @change="err='';const f=$event.target.files[0];if(!f){preview=null;return}if(f.size>2097152){err='Ukuran foto maksimal 2 MB.';$event.target.value='';preview=null;return}preview=URL.createObjectURL(f)">
  </label>
 </div>
 {{ $slot }}
 <p x-show="err" x-cloak class="err" x-text="err"></p>
 @error($name)<p class="err">⚠ {{ $message }}</p>@enderror
</div>
