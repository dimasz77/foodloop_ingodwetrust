@extends('layout')
@section('title','Impact')
@section('content')
<h1 class="text-3xl font-extrabold">Real Impact of FoodLoop</h1><p class="mb-6 text-slate-600">Setiap makanan yang diselamatkan memberi dampak nyata bagi masyarakat dan lingkungan.</p>
@include('_stats')
<h2 class="mb-3 mt-10 text-2xl font-bold">Why FoodLoop Matters</h2>
<div class="grid gap-4 md:grid-cols-3">@foreach([['🌍','Lower Carbon Emissions','Mencegah emisi metana dari sampah makanan di TPA.'],['🍽️','Feed Communities','Makanan layak disalurkan kepada penerima manfaat.'],['♻️','Earth First','Sisa organik diolah menjadi kompos atau maggot BSF.']] as [$i,$t,$d])<div class="card"><div class="text-2xl">{{ $i }}</div><b>{{ $t }}</b><p class="text-sm text-slate-600">{{ $d }}</p></div>@endforeach</div>
<h2 class="mb-3 mt-10 text-2xl font-bold">Impact by Categories</h2>
@php $off=0; @endphp
<div class="flex flex-wrap items-center gap-8"><svg viewBox="0 0 42 42" width="200" height="200" role="img" aria-label="Diagram donat dampak per kategori" style="transform:rotate(-90deg)">
@foreach($cats as [$n,$kg,$c])@php $l=$kg/$sum*100; @endphp<circle r="15.9" cx="21" cy="21" fill="none" stroke="{{ $c }}" stroke-width="6" stroke-dasharray="{{ $l }} {{ 100-$l }}" stroke-dashoffset="{{ -$off }}"/>@php $off+=$l; @endphp @endforeach</svg>
<ul>@foreach($cats as [$n,$kg,$c])<li><span class="mr-2 inline-block h-3 w-3 rounded-full" style="background:{{ $c }}"></span>{{ $n }}: {{ number_format($kg,0,',','.') }} kg ({{ round($kg/$sum*100) }}%)</li>@endforeach</ul></div>
<h2 class="mb-3 mt-10 text-2xl font-bold">Community Leaderboard</h2>
<div class="overflow-x-auto rounded-2xl"><table class="w-full bg-slate-800 text-left text-white"><thead><tr><th class="p-3">Rank</th><th class="p-3">Contributor</th><th class="p-3">Contributed</th><th class="p-3">Impact</th></tr></thead><tbody>
@foreach($board as $i=>$b)<tr class="border-t border-white/20"><td class="p-3">{{ $i+1 }}</td><td class="p-3">{{ $b['name'] }}</td><td class="p-3">{{ number_format($b['kg'],0,',','.') }} kg</td><td class="p-3">{{ number_format($b['co2'],1,',','.') }} kg CO₂e</td></tr>@endforeach</tbody></table></div>
<div class="mt-10 rounded-2xl bg-emerald-800 p-8 text-center text-white"><h2 class="mb-3 text-2xl font-bold">Tertarik jadi penyelamat pangan minggu ini?</h2><a class="btn !bg-white !text-emerald-900" href="{{ route('report.create') }}">Report Waste</a></div>
@endsection
