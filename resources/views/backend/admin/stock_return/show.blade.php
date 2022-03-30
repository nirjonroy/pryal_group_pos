@extends('layouts.backend.app')
@section('page_title')
@push('css')
<link rel="stylesheet" href="{{asset('backend/links')}}/assets/libs/bootstrap-datepicker/bootstrap-datepicker.css">
<style>
th.text-right{

    color:red;
}
</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <a class="btn btn-primary btn-sm no-print mt-3 mb-3" style="width:100px" onclick="imprimir()">Print</a>
    <div class="row">
        
       <div class="col-md-12">
            <div class="card" >
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-sm-6 pb-4">
                            @include('info.info')
                        </div>

                        <div class="col-sm-6 pb-4 text-right">
                            <h6 class="text-big text-large font-weight-bold mb-3">Transfar</h6>

                            <div class="mb-1"><b>Sell Invoice</b> : {{ $sell->invoice_no }}
                            <strong class="font-weight-semibold"></strong>
                            </div>

                            <div class="mb-1"><b>Sell Amount</b> : {{ $sell->total_price }}
                            <strong class="font-weight-semibold"></strong>
                            </div>


                            <div class="mb-1"><b>Project </b>: 
                            <strong class="font-weight-semibold"></strong>
                            </div>

                            <div class="mb-1"><b>Note </b>: {{ $sell->description }}
                            <strong class="font-weight-semibold"></strong>
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                       <div class="col-md-12">
                           
                            <div class="pull-left">
                            <div class="font-weight-bold mb-2">Customer :</div>
                            <div>
                               <b> Name </b> : {{$sell->customer->name}}
                            </div>
                            <div>
                                <b>Address</b> : {{$sell->customer->address}}
                            </div>
                            <div><b>Phone </b>: {{$sell->customer->contract_phone}}
                            </div>
                        </div>

                        
                            <div class="pull-right" style="margin-top:-80px;">
                                <div class="font-weight-bold mb-2 "></div>
                                <table>
                                    <tbody>
                                        

                                        

                                        


                        
                                    </tbody>
                                </table>
                            </div>
                       
                       </div>
                    </div><hr>

                    <h4>Transfar Details</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Product</th>
                                    <th>Product Unit</th>
                                    <th>quantity</th>
                                    <th>unit Price</th>
                                    <th>total Price</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($sell->sellDetails as $details)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $loop->index+1 }}
                                        </strong>
                                    </td>

                                    <td>{{ date('d.m.y', strtotime($details->created_at)) }}</td>

                                    <td>{{ $details->invoice_no }}</td>
                                    <td>{{ $details->products->name }}</td>
                                    <td>{{ $details->products->unit->name }}</td>
                                    <td>{{ $details->quantity }}</td>
                                    <td>{{ $details->unit_price }}</td>
                                    <td>{{ number_format($details->quantity * $details->unit_price,2) }}</td>
                                    <td>{{ $details->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <th colspan="7" class="text-right">Total=</th>
                                <td>{{$sell->total_price}}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="text-muted">

                    </div>
                </div>


            </div>
        </div>
    </div>

</div>


@push('js')

@endpush
@endsection
