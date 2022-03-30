@extends('layouts.backend.app')
@section('page_title') 

@section('content')


<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <a class="btn btn-primary btn-sm no-print" style="width:100px" onclick="imprimir()">Print</a>
    <div class="row">
       <div class="col-md-12">
            <div class="card">
                <div class="card-body p-2" >
                    
                    <div class="row">
                        <div class="col-sm-6 pb-4">
                            <div class="media align-items-center mb-4">
                            <a href="#" class="navbar-brand app-brand demo py-0 mr-4">
                            <span class="app-brand-logo demo">
                            <img src="{{ asset('backend/links') }}/assets/img/logo.png" alt="Brand Logo" class="img-fluid" width="40">
                            </span>
                            <span class="app-brand-text demo font-weight-bold text-dark ml-2 no-print">{{ config('app.name') }}</span>
                            </a><br>
                            </div>
                        </div>

                        <div class="col-sm-6 text-right pb-4">
                            
                        </div>
                    </div>
                    <hr class="mb-4">
                    
                    <div class="row">
                       <div class="col-md-12">
                            <div class="pull-left">
                            <div class="font-weight-bold mb-2">Company Information:</div>
                            <div>
                               <b> company Name </b> :  {{ $row->project->companies->name }}
                            </div>
                            <div>
                                <b>company Address</b> :  {{ $row->project->companies->address }}
                            </div>
                            <div><b>company Phone </b>: {{ $row->project->companies->contract_phone }}</div>
                        </div>



                      
                            <div class="pull-right" style="margin-top:-80px;">
                                <div class="font-weight-bold mb-2 ">Project Info:</div>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th class="pr-3">Project Name:</th>
                                            <td>
                                                <strong>
                                                    {{ $row->project->name }}
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="pr-3">Project Type:</th>
                                            <td>
                                                <strong>
                                                    {{ $row->project->type->name }}
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="pr-3">Project Created at:</th>
                                            <td>
                                                <strong>
                                                    {{ date('d.m.Y', strtotime($row->project->created_at)) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                       
                       </div>
                    </div><hr>
                    <h4>Receive Amount Information</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                   <th>Date</th>
                                    <th>Company</th>
                                    <th>Project Name</th>
                                    <th>Project Type</th>
                                    <th>Payment Amount</th>
                                    <th>Description of Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{date('d.m.Y', strtotime($row->created_at))}}
                                    </td>
                                    <td>{{  $row->project->companies->name }}</td>
                                    
                                    <td>{{ $row->project->name }}</td>
                                    <td>{{ $row->project->type->name }}</td>
                                    <td>{{  $row->payment_amount }}</td>
                                    <td>
                                        {{ $row->note }}

                                    </td>
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
