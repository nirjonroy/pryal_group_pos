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
<link rel="stylesheet" href="{{ asset('backend/links') }}/assets/libs/datatables/datatables.css">
@endpush

@section('content')
<div class="container-fluid" id="print">
    @include('layouts.backend.partial.success_error_status_message')

    <h4 class="font-weight-bold py-3 mb-0">Project</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.project.create') }}">Project Create</a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form class="no-print">
                        <div class="row">

                             <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                 <label class="form-label">Company Type</label>
                                <select class="form-control" name="com_type_id" onchange="this.form.submit()">
                                    <option value="" {{(request()->com_type_id=='')?'selected':''}}>All</option>
                                    @foreach($comTypes as $company_type)
                                    <option value="{{$company_type->id}}" {{(request()->com_type_id==$company_type->id)?'selected':''}}>{{$company_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label class="form-label">Company List</label>
                                <select class="form-control" name="company_id" onchange="this.form.submit()">
                                    <option value="" {{(request()->company_id=='')?'selected':''}}>All</option>
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}" {{(request()->company_id==$company->id)?'selected':''}}>{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-label">Project Status</label>
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="" hidden>Select A Status</option>
                                    <option value="0" {{(request()->status==0)?'selected':''}}>Running</option>
                                    <option value="1" {{(request()->status==1)?'selected':''}}>Complete</option>
                                    <option value="2" {{(request()->status==2)?'selected':''}}>Work By Done</option>
                                    <option value="3" {{(request()->status==3)?'selected':''}}>Partner Investment</option>
                                    <option value="" {{(request()->status=='')?'selected':''}}>All</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-label">All Project</label>
                                <select class="form-control" name="project_id" onchange="this.form.submit()">
                                    <option value="" {{(request()->project_id=='')?'selected':''}}>All Project</option>
                                    @foreach($ps as $item)
                                    <option value="{{$item->id}}" {{(request()->project_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                 <label class="form-label">Project Type</label>
                                <select class="form-control" name="type_id" onchange="this.form.submit()">
                                    <option value="" {{(request()->type_id=='')?'selected':''}}>Project Type</option>
                                    @foreach($types as $item)
                                    <option value="{{$item->id}}" {{(request()->type_id==$item->id)?'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                                <br><br><a class="btn btn-xs btn-primary" onclick="imprimir()">Print</a>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>

                        </div>
                    </form>
                    <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>
                    
                <div class="card-datatable table-responsive" >
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th >Project Name</th>
                                <th >Company Name</th>
                                <th>Type Of Project</th>
                                <th>Project Value</th>
                                <th>Received Amount</th>
                                <th>Total Purchase</th>
                                <th>Total Expense</th>
                                <th>Stock Transfer</th>
                                <th>Stock Return</th>
                                <th>Total Profit/Loss</th>
                                <th>Status</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_payment=0;
                                $total_purchase=0;
                                $total_expense=0;
                                $total_total_due=0;
                                $total_transfer=0;
                                $total_transfer_return=0;
                                $n_total_transfer_return = 0;
                            @endphp
                
                            @foreach($projects as $key=> $project)

                            @php
                                $total_payment +=$project->projectPayment->sum('payment_amount');
                                $purchase=$project->purchase->sum('total_price');
                                $total_purchase +=$purchase;
                                $expense=$project->expense->sum('total_price');
                                $total_expense +=$expense;
                                $purchase_and_expense=$purchase + $expense;
                                $transfer=$project->sell->sum('total_price');
                                $total_transfer +=$transfer;
                                
                                $transfer_return=$project->sell_return->sum('total_price');
                                $total_transfer_return=$transfer_return;
                                
                                $n_transfer_return= $project->sell_return->sum('total_price'); 
                            $n_total_transfer_return+=$n_transfer_return; 
                            
                                $profit_loss=($project->projectPayment->sum('payment_amount') -$purchase_and_expense);

                                $total_total_due +=$profit_loss;
                            @endphp
                            <tr>
                                <td>{{ $key+ $projects->firstItem() }}</td>
                                 <td >{{$project->name}}</td>
                                <td >{{ $project->companies ?$project->companies->name :''}}</td>
                                <td >{{$project->type->name}}</td>
                                <td>{{$project->project_value}}</td>
                                <td>{{number_format($project->projectPayment->sum('payment_amount'),2)}}</td>
                                <td>{{number_format($purchase,2)}}</td>
                                <td>{{number_format($expense,2)}}</td>
                                 <td>{{number_format($transfer,2)}}</td>
                                <td>{{number_format($transfer_return,2)}}</td>
                                <td >
                                    @if($profit_loss<0)
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-danger">Loss</span>
                                    @else
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-success">Profit</span>
                                    @endif
                                </td>
                                <td>{{array_search($project->working_status,\App\Unit::getStatus())}}</td>

                                <td  class="no-print">
                                    @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                    <a style="color: #fff" class="btn btn-xs btn-primary" id="btn-modal" data-href="{{ action('Backend\Admin\Project\ProjectController@getStatusModal',$project->id)}}">Update Status</a>
                                    @endif

                                    @if(auth()->user()->hasRole('admin'))
                                       <a style="color: #fff" class="btn btn-xs btn-primary" id="btn-modal" data-href="{{ action('Backend\Admin\Project\ProjectController@getStatusModal',$project->id)}}">Update Status</a>
                                    @endif


                                     @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                         <a class="btn btn-xs btn-success"  
                                    href="{{route('admin.project.edit',$project->id) }}"><i class="fa fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(auth()->user()->hasRole('admin'))
                                      <a class="btn btn-xs btn-success"  
                                    href="{{route('admin.project.edit',$project->id) }}"><i class="fa fa-edit"></i>
                                    </a>
                                    @endif

                                     <!-- <a class="btn btn-xs btn-info" id="btn-modal" data-href="{{ action('Backend\Admin\Project\ProjectController@getDetailsModal',$project->id)}}">Details</a> -->
                                </td>
                            </tr>
                            @endforeach

                            <tr>
                                <th colspan="4"><strong>Total</strong></th>
                                <th><strong>{{number_format($projects->sum('project_value'),2)}}</strong></th>
                                <th><strong>{{number_format($total_payment,2)}}</strong></th>
                                <th><strong>{{number_format($total_purchase,2)}}</strong></th>
                                <th><strong>{{number_format($total_expense,2)}}</strong></th>
                                <th><strong> {{number_format($total_transfer,2)}}</strong></th>
                                <th><strong> {{number_format($n_total_transfer_return,2)}}</strong></th>
                                <th><strong>{{number_format($total_total_due,2)}}</strong></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
               <p>{!! urldecode(str_replace("/?","?",$projects->appends(Request::all())->render())) !!}</p>
            </div>
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
                $('div#container').html(result).modal('show');
            }
        });
    });

     var amount=0;
     $(document).on('keyup','#payment_amount',function(){
         amount=$(this).val();
         var due=$('#due').val();
         update_amount=(due-amount);
         $('#due_amount').text(update_amount);


     });
</script>


@endpush
@endsection
