<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'FoodLoop') – FoodLoop</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','system-ui','sans-serif'],serif:['Fraunces','Georgia','serif']}}}}</script>
<style type="text/tailwindcss">@layer components{.btn{@apply inline-flex items-center justify-center rounded-full bg-green-800 px-4 py-2 text-sm font-bold text-white hover:bg-green-900 disabled:opacity-50}.btn-o{@apply border border-slate-300 bg-white !text-slate-800 hover:bg-slate-50}.card{@apply rounded-2xl border border-black/5 bg-white p-5 shadow-sm}.inp{@apply w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-green-700 focus:ring-green-700}.lbl{@apply mb-1 block text-sm font-semibold text-slate-700}}[x-cloak]{display:none!important}</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
@stack('head')</head>
<body class="bg-[#f6f4ec] font-sans text-slate-800 antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-2 focus:top-2 focus:z-50 focus:bg-white focus:p-2">Lewati ke konten</a>
<div id="main">@yield('shell')</div>
@stack('scripts')
</body></html>
