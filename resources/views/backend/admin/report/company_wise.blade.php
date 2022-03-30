@extends('layouts.backend.app')
@section('page_title') 
@push('css')
<style>
.table th, .table td {
    padding: 5px 0 0px 0px;
    /* vertical-align: top; */
    border-top: 1px solid rgba(24,28,33,.06);
}
</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1" id="print">
   <h4 class="font-weight-bold py-3 mb-0">Company Wise running Project Report</h4>
    <div class="row" >
         
        <div class="col-md-12">
            <div class="card">
                <div class="col-md-12">
                    <form class="no-print">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <label>Company Type :</label>
                                <select class="form-control" name="type_id" onchange="this.form.submit()">
                                    
                                    <option value="" {{request()->type_id=='' ?'selected':''}}>All</option>
                                    @foreach($comTypes as $type)
                                    <option value="{{$type->id}}" {{request()->type_id==$type->id ?'selected':''}}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-offset-4 col-md-4">
                                <label>Company :</label>
                                <select class="form-control" name="id" onchange="this.form.submit()">
                                    <option value="" {{request()->id=='' ?'selected':''}}>All</option>
                                    @foreach($coms as $company)
                                    <option value="{{$company->id}}" {{request()->id==$type->id ?'selected':''}}>{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                            <div class="form-group col-md-1">
                                <br><br><a class="btn btn-sm btn-primary no-print" onclick="imprimir()">Print</a>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>
                
                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company Name</th>
                                <th>Contact Person</th>
                                <th >Address</th>
                                <th>Total Project</th>
                                <th>Total Project Amount</th>
                                <th >Total Receive Amount</th>
                                <th>Total Purchase</th>
                                <th>Total Expense</th>
                                <th >Profit/Due</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count=1;
                                $project=0;
                                $project_value=0;
                                $purchase=0;
                                $receive=0;
                                $expense=0;
                                $total_due=0;
                            @endphp
                            @foreach($companies as $key=>$company)

                            @php
                                $project +=$company->projects->count();
                                $project_value +=$company->projects->sum('project_value');
                                $purchase +=$company->purchase->sum('total_price');
                                $receive +=$company->payments->sum('payment_amount');
                                $expense +=$company->expense->sum('total_price');
                                $due =$company->payments->sum('payment_amount') -($company->purchase->sum('total_price') +$company->expense->sum('total_price'));

                                $total_due +=$due;

                                if($company->projects->count() <1){
                                    continue;
                                }
                            @endphp
                            <tr>
                                <td>{{$count++ }}</td>
                                <td>{{$company->name}}</td>
                                <td>{{$company->contract_person}}</td>
                                <td >{{$company->address}}</td>
                                <td>{{number_format($company->projects->count(),2)}}</td>
                                <td>{{number_format($company->projects->sum('project_value'),2)}}</td>
                                <td>{{number_format($company->payments->sum('payment_amount'),2)}}</td>
                                <td>{{number_format($company->purchase->sum('total_price'),2)}}</td>
                                <td>{{number_format($company->expense->sum('total_price'),2)}}</td>

                                <td>
                                    @if($due<0)
                                        {{number_format($due,2)}}<span class="badge badge-pill badge-danger">Loss</span>
                                    @else
                                        {{number_format($due,2)}}<span class="badge badge-pill badge-success">Profit</span>
                                    @endif
                                </td>

                                <td class="no-print"><a class="btn btn-info btn-sm" href="{{action('Backend\ReportController@companyRunningDetails',$company->id)}}">Details</a></td>
                            </tr>
                            @endforeach
                            <tr>
                                <th colspan="4"><strong>Total</strong></th>
                                <th><strong>{{number_format($project,2)}}</strong></th>
                                <th><strong>{{number_format($project_value,2)}}</strong></th>
                                <th><strong>{{number_format($receive,2)}}</strong></th>
                                <th><strong>{{number_format($purchase,2)}}</strong></th>
                                <th><strong>{{number_format($expense,2)}}</strong></th>
                                <th><strong>{{number_format($total_due,2)}}</strong></th>
                            </tr>
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
