@extends('layouts.backend.app')
@section('page_title') Home Page @endsection
@push('css')
<style>
.red{
    color: red;
}
.well{
    border:1px solid #000;
    height: 85px;
    padding: 10px;
}

.well1{
    border:3px solid #000;
    height: 85px;
}


</style>
@endpush
@section('content')

<h3 style="padding: 5px;">Dashboard</h3>
<div class="col-md-12" style="padding:10px; margin: 5px;">

<div class="row text-center">
                

                <div class="col-md-3 well" >
                    <b class="">Purchase</b><br>
                    <b class="red">{{number_format($purchase_total,2)}}</b>
                   
                </div>
                
                <div class="col-md-3 well">
                    <b>Sell</b><br>
                    <b class="red">{{number_format($sell_price,2)}}</b>
                </div>
                
                <div class="col-md-3 well">
                    <b>Expense</b><br>
                    <b class="red">{{number_format($expense,2)}}</b>
                </div>
                
                 @php 
                $ep= $expense+$purchase_total;

                @endphp
                <div class="col-md-3 well">
                    <b> Profit/Loss </b><br>
                    <b class="red">{{number_format($sell_price- $ep,2)}}</b>
                </div>
                

            </div><hr>

          



</div>





@endsection
