@extends('layouts.backend.app')
@section('page_title')
@push('css')
<style>

</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1" id="print">
    <div class="row">
        <div class="col-md-12">
                    <form class="no-print">

                        <div class="row">

                          <div class="col-md-4">
                              <label>Status:</label>
                              <select class="form-control" name="status" onchange="this.form.submit()">
                                <option value="" hidden>Select A Status</option>
                                <option value="0" {{request()->status=='0' ?'selected':''}}>Running</option>
                                <option value="1" {{request()->status=='1' ?'selected':''}}>Complete</option>
                                <option value="2" {{request()->status=='2' ?'selected':''}}>Work Done</option>
                                <option value="3" {{request()->status=='3' ?'selected':''}}>Partner Investment</option>
                                <option value=""  {{(request()->status=='')?'selected':''}}>All</option>
                              </select>
                          </div>

                          <div class="col-md-4">
                              <label>Project:</label>
                              <select class="form-control" name="project_id" onchange="this.form.submit()">
                                <option value="" {{(request()->project_id=='')?'selected':''}}>All</option>
                                @foreach($project as $item)
                                <option value="{{$item->id}}" {{(request()->project_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                                @endforeach
                              </select>
                          </div>
                        </div>
                    </form>
                  </div>

        <div class="col-md-12">
            <div class="form-group ">

                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                            
            <a class="btn btn-primary no-print mt-3 mb-3" onclick="imprimir()">Print</a>
            <div class="card p-4">
                <div class="row">
                        <div class="col-sm-6 pb-4">
                            @include('info.info')
                        </div>
                    </div>
                    <hr class="mb-4">

                    <div class="row">
                        <div class="col-sm-6 col-md-6 mb-4">
                            <div class="font-weight-bold mb-2">Company :</div>
                            <div>
                               <b> Name </b> : {{ $company->name }}
                            </div>
                            <div>
                                <b>Address</b> : {{ $company->address }}
                            </div>
                            <div><b>Phone </b>: {{ $company->contract_phone }}
                            </div>
                        </div>

                        <div class="col-sm-6  col-md-6  mb-4">
                            <div class="pull-right" style="margin-right:5px;">
                                <div class="font-weight-bold mb-2 ">Project :</div>
                                 <table>
                                    <tbody>
                                        <tr>
                                            <th class="pr-3">Total Project :</th>
                                            <td>
                                                <strong>
                                                    {{number_format($projects->count(),2)}}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3">Total Project Value:</th>
                                            <td>
                                                <strong>
                                                   {{number_format($projects->sum('project_value'),2)}}
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="pr-3">Total Receive Amount:</th>
                                            <td>
                                                <strong>
                                                  @php
                                                  $pay=0
                                                  @endphp
                                                  @foreach($projects as $data)
                                                    @php
                                                    $pay+=$data->projectPayment->sum('payment_amount');
                                                    @endphp
                                                   @endforeach
                                                   {{number_format($pay,2)}}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr style="background:#eee">
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Project Name</th> 
                                <th>Project Value</th> 
                                <th>Payment Amount</th> 
                                <th>Total Due</th> 
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($projects as $project)
                           <tr style="background:green;color:#ccc">
                                <th>{{ $loop->index+1 }}</th>
                                <th>{{date('d.m.Y', strtotime($project->created_at))}}</th>
                                <th>{{$project->name}}</th>
                                <th>{{$project->project_value}}</th>
                           </tr>

                           @foreach($project->projectPayment as $payment)
                           <tr>
                               <td>{{ $loop->index+1 }}</td>
                               <td>{{date('d.m.Y', strtotime($payment->created_at))}}</td>
                               <td>{{$payment->note}}</td>
                               <td></td>
                               <td>{{$payment->payment_amount}}</td>
                           </tr>
                           @endforeach
                           <tr>
                               <th></th>
                               <th></th>
                               <th></th>
                               <th style="color:red">Total Payment=</th>
                               <th>{{number_format($project->projectPayment->sum('payment_amount'),2)}}</th>
                               <th>{{number_format($project->project_value-$project->projectPayment->sum('payment_amount'),2)}}</th>
                           </tr>
                            </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
@endpush
@endsection
