@extends('layouts.backend.app')
@section('page_title')
@push('css')
<style>

    @media print{
        .table td{
             white-space: normal !important;
             width: 20px solid !important;
        }
         .table th{
            white-space: normal !important;
            border: 1px solid;
            page-break-inside:avoid;
        }

    }

</style>
@endpush

@section('content')

<div class="container-fluid flex-grow-1 container-p-y" >
    <div class="row" id="print">
        <h4 class="font-weight-bold py-3 mb-0 ml-3">Project Wise Report</h4>
        <div class="col-md-12">
            <div class="card">
               <div class="col-md-12">
                    <form class="no-print">
                        <div class="row no-print">
                            <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                    <option value="" hidden>Select</option>
                                    <option value="alldata" {{(request()->alldata=='alldata')?'selected':''}}>Get All Data</option>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>Company Type:</label>
                                <select class="form-control" name="company_type_id" onchange="this.form.submit()">
                                    <option value="" {{(request()->company_type_id=='')?'selected':''}}>Company Type</option>
                                    @foreach($comTypes as $item)
                                    <option value="{{$item->id}}" {{(request()->company_type_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Company:</label>
                                <select class="form-control" name="company_id" onchange="this.form.submit()">
                                    <option value="" hidden>Select A Company</option>
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}" {{(request()->company_id==$company->id)?'selected':''}}>{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Project Status:</label>
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="" hidden>Select A Status</option>
                                    <option value="0" {{(request()->status==0)?'selected':''}}>Running</option>
                                    <option value="1" {{(request()->status==1)?'selected':''}}>Complete</option>
                                    <option value="2" {{(request()->status==2)?'selected':''}}>Work Done</option>
                                    <option value="3" {{(request()->status==3)?'selected':''}}>Partner Investment</option>
                                    <option value="" {{(request()->status=='')?'selected':''}}>All</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Project Type:</label>
                                <select class="form-control" name="type_id" onchange="this.form.submit()">
                                    <option value="" {{(request()->type_id=='')?'selected':''}}>Project Type</option>
                                    @foreach($types as $item)
                                    <option value="{{$item->id}}" {{(request()->type_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Project:</label>
                                <select class="form-control" name="project_id" onchange="this.form.submit()">
                                    <option value="" hidden>Select A Project</option>
                                    @foreach($ps as $item)
                                    <option value="{{$item->id}}" {{(request()->project_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
 

                        </div>
                    </form>

                    <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                    <a class="btn btn-sm btn-success no-print" href="{{action('Backend\ReportController@ProjectWise')}}">Refresh</a>
                    
                   <a class="btn btn-sm btn-primary no-print" onclick="imprimir()">Print</a>
              
                </div>
                  <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>
                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered" id="table" border="1">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company Name</th>
                                <th>Project Name</th>
                                <th>Type Of Project</th>
                                <th>Status</th>
                                <th>Project Value</th>
                                <th>Received Amount</th>
                                <th>Total Purchase</th>
                                <th>Total Expense</th>
                                <th>Total Transfer</th>
                                <th>Total Return</th>
                                
                                <th>Total Profit/Loss</th>
                                <th class="no-print">Action</th>
                            
                        </thead>
                        <tbody>

                            @php
                                $total_payment=0;
                                $total_purchase=0;
                                $total_expense=0;
                                $total_total_due=0;
                                $total_transfer=0;
                                $total_transfer_return=0;
                            @endphp
                
                            @foreach($projects as $key=>$project)

                            @php
                                $total_payment +=$project->projectPayment->sum('payment_amount');
                                $purchase=$project->purchase->sum('total_price');
                                $transfer=$project->sell->sum('total_price');
                                $total_transfer +=$transfer;

                                $transfer_return=$project->sell_return->sum('total_price');
                                $total_transfer_return=$transfer_return;

                                $total_purchase +=$purchase;
                                $expense=$project->expense->sum('total_price');
                                $total_expense +=$expense;
                                $purchase_and_expense=$purchase + $expense +$transfer;
                                $profit_loss=(($project->projectPayment->sum('payment_amount') + $transfer_return) - $purchase_and_expense);
                                $total_total_due +=$profit_loss;
                            @endphp
                            <tr>
                                <td>{{$key+ $projects->firstItem()}}</td>
                                <td>{{$project->companies ?$project->companies->name:''}}</td>
                                <td>{{$project->name}}</td>
                                <td>{{$project->type->name}}</td>
                                <td>{{($project->working_status==1)?'Complete':'Running'}}</td>
                                <td>{{$project->project_value}}</td>
                                <td>{{number_format($project->projectPayment->sum('payment_amount'),2)}}</td>
                                <td>{{number_format($purchase,2)}}</td>
                                <td>{{number_format($expense,2)}}</td>
                                <td>{{number_format($transfer,2)}}</td>
                                <td>{{number_format($transfer_return,2)}}</td>
                                
                                <td>
                                    @if($profit_loss<0)
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-danger">Loss</span>
                                    @else
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-success">Profit</span>
                                    @endif
                                </td>
                                <td class="no-print">
                                    <a class="btn btn-primary btn-sm" href="{{action('Backend\ReportController@projectDetails',$project->id)}}">Details</a>
                                </td>
                            </tr>
                            @endforeach

                            <tr>
                                <th colspan="4" ><strong></strong></th>
                                <th ><strong>This Page Summery</strong></th>
                                <th><strong>{{number_format($projects->sum('project_value'),2)}}</strong></th>
                                <th><strong>{{number_format($total_payment,2)}}</strong></th>
                               
                                <th><strong>{{number_format($total_purchase,2)}}</strong></th>
                                <th><strong>{{number_format($total_expense,2)}}</strong></th>
                                 <th><strong>{{number_format($total_transfer,2)}}</strong></th>
                                <th><strong>{{number_format($total_transfer_return,2)}}</strong></th>
                                <th><strong>{{number_format($total_total_due,2)}}</strong></th>
                            </tr>


                            @php
                                $p_recive_amount = 0;
                                $p_purchase_amount = 0;
                                $p_expense_amount = 0;
                                $p_due_amount = 0;
                                $p_total_transfer = 0;
                                $p_total_transfer_return = 0;
                            @endphp
                            @foreach($this_page_projects as $value)

                            @php
                            $p_recive_amount += $value->projectPayment->sum('payment_amount'); 

                            $p_purchase= $value->purchase->sum('total_price'); 
                            $p_purchase_amount+=  $p_purchase; 

                            $p_transfer= $value->sell->sum('total_price'); 
                            $p_total_transfer+=$p_transfer;

                            $p_transfer_return= $value->sell_return->sum('total_price'); 
                            $p_total_transfer_return+=$p_transfer_return; 


                            $p_expense= $value->expense->sum('total_price');
                            $p_expense_amount+= $p_expense;


                            $p_purchase_expense_amount = $p_purchase +  $p_expense + $p_transfer;
                                
                            $p_profit_loss = (($value->projectPayment->sum('payment_amount') + $p_transfer_return) -$p_purchase_expense_amount);

                            $p_due_amount+= $p_profit_loss ; 

                            @endphp

                            @endforeach

                            <tr>
                                <th colspan="4"></th>
                                <th ><strong>Summery With Previous Pages</strong></th>
                                <th><strong>{{number_format($this_page_projects->sum('project_value'),2)}}</strong></th>
                                <th><strong>{{number_format($p_recive_amount,2)}}</strong></th>
                                 <th><strong>{{number_format($p_purchase_amount,2)}}</strong></th>
                                <th><strong>{{number_format($p_expense_amount,2)}}</strong></th>
                                <th><strong>{{number_format($p_total_transfer,2)}}</strong></th>
                                <th><strong>{{number_format($p_total_transfer_return,2)}}</strong></th>
                               
                                <th><strong>{{number_format($p_due_amount,2)}}</strong></th>
                                
                            </tr>
                        

                            @php
                            $total_recive_amount = 0;
                            $total_purchase_amount = 0;
                            $total_expense_amount = 0;
                            $total_due_amount = 0;
                            $n_total_transfer = 0;
                            $n_total_transfer_return = 0;
                            @endphp
                            @foreach($project_total as $value)

                            @php
                            $total_recive_amount += $value->projectPayment->sum('payment_amount'); 

                            $ttl_purchase= $value->purchase->sum('total_price'); 
                                $total_purchase_amount+=  $ttl_purchase; 


                            $n_transfer= $value->sell->sum('total_price'); 
                            $n_total_transfer+=$n_transfer;

                            $n_transfer_return= $value->sell_return->sum('total_price'); 
                            $n_total_transfer_return+=$n_transfer_return; 

                            $ttl_expense= $value->expense->sum('total_price');
                            $total_expense_amount+= $ttl_expense;


                            $total_purchase_expense_amount = $ttl_purchase +  $ttl_expense +$n_transfer;
                                
                            $total_profit_loss = (($value->projectPayment->sum('payment_amount') + $n_transfer_return) -$total_purchase_expense_amount);

                            $total_due_amount+= $total_profit_loss ; 

                            @endphp

                            @endforeach
                            <tr>
                                <th colspan="4"></th>
                                <th ><strong>Total Summery</strong></th>
                                <th><strong>{{number_format($project_total->sum('project_value'),2)}}</strong></th>
                                <th><strong>{{number_format($total_recive_amount,2)}}</strong></th>
                                <th><strong>{{number_format($total_purchase_amount,2)}}</strong></th>
                                <th><strong>{{number_format($total_expense_amount,2)}}</strong></th>
                                <th><strong>{{number_format($n_total_transfer,2)}}</strong></th>
                                <th><strong>{{number_format($n_total_transfer_return,2)}}</strong></th>
                                
                                <th><strong>{{number_format($total_due_amount,2)}}</strong></th>
                                
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <p class="no-print">{!! urldecode(str_replace("/?","?",$projects->appends(Request::all())->render())) !!}</p>
        </div>
    </div>
</div>

<div class="modal fade container" id="container" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>

@push('js')
<script src="{{ asset('backend/links')}}/assets/js/ajax.js"></script>
<script src="{{ asset('backend/links')}}/assets/js/bootstrap.js"></script>
<script type="text/javascript">
$(document).on( 'click', '#btn-modal', function(e){
e.preventDefault();

$.ajax({
    url: $(this).data("href"),
    dataType: "html",
    success: function(result){
        $('.container').html(result).modal('show');
    }
});
});
</script>




@endpush
@endsection
