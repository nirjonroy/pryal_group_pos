@extends('layouts.backend.app')
@section('page_title')

@section('content')


<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <a class="btn btn-primary btn-sm mb-3 no-print" style="width:100px" onclick="imprimir()">Print</a>
    <div class="row">
       <div class="col-md-12">
            <div class="card">
                <div class="card-body p-2" >
                    <div class="row">
                        <div class="col-sm-6 pb-4">
                            @include('info.info')
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="font-weight-bold mb-2">Supplier Information:</div>
                            <div>
                               <b> Name </b> :  {{ $row->suppliers->name }}
                            </div>
                            <div>
                                <b>Address</b> :  {{ $row->suppliers->address }}
                            </div>
                            <div><b>Phone </b>: {{ $row->suppliers->contract_phone }}</div>
                        </div>


                    

                        <div class="col-sm-6">
                            
                        </div>
                    </div><hr>
                    <h4>Payment Information</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Supplier</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Method</th>
                                    <th>Description of Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{date('d.m.Y', strtotime($row->created_at))}}</td>
                                    <td>{{ $row->invoice_no }}</td>
                                    <td>{{  $row->suppliers->name }}</td>
                                    <td>{{  $row->total_price }}</td>
                                    <td>{{  $row->method->method }}</td>
                                    <td>{{ $row->note }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-muted">

                    </div>
                </div>


            </div>
        </div>
    </div>

</div>

@endsection
