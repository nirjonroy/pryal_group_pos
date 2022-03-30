@foreach($infos as $info)
<div class="media align-items-center mb-4">
    <a href="{{ route('home') }}" class="navbar-brand app-brand demo py-0 mr-4">
    <span class="app-brand-logo demo">
    <img src="{{ asset('backend/links') }}/assets/img/MS_Priyal_Trades.jpeg" alt="Brand Logo" class="img-fluid" width="50" style="border-radius:100px">
    </span>
    <span class="app-brand-text demo font-weight-bold text-dark ml-2 no-print">{{ $info->name }}</span>
    </a>
</div>
<div class="mb-1">
    Office address : {{ $info->address }}<span class="app-brand-text demo font-weight-bold text-dark ml-2"></span>
</div>
<div class="mb-1">
    Phone :{{ $info->phone }} <span class="app-brand-text demo font-weight-bold text-dark ml-2"></span>
</div>
@endforeach