@if(session('ok'))<div role="status" class="mb-4 rounded-xl bg-lime-200 p-3 font-semibold text-green-900">{{ session('ok') }}</div>@endif
@if($errors->any())<div role="alert" class="mb-4 rounded-xl bg-red-100 p-3 text-red-900"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
