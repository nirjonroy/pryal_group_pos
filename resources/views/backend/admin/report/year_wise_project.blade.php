@extends('layouts.backend.app')
@section('page_title') 
@push('css')
<style>

</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1" id="print">
    <h4 class="font-weight-bold py-3 mb-0">Complete Yearly Project Details Report</h4>
     <a class="btn btn-sm btn-primary no-print" onclick="imprimir()">print</a><br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                 <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>
                <div class="table-responsive" >
                    <table class="datatables-demo table table-striped table-bordered" id="table" border="1">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th >Company Name</th>
                                <th>Project Name</th>
                                <th>Type Of Project</th>
                                <th >Status</th>
                                <th>Project Value</th>
                                <th>Received Amount</th>
                                <th>Total Purchase</th>
                                <th>Total Expense</th>
                                <th>Total Profit/Loss</th>
                                <th class="no-print">Action</th>
                            
                        </thead>
                        <tbody>

                            @php
                                $total_payment=0;
                                $total_purchase=0;
                                $total_expense=0;
                                $total_total_due=0;
                            @endphp
                
                            @foreach($projects as $key=>$project)

                            @php
                                $total_payment +=$project->projectPayment->sum('payment_amount');
                                $purchase=$project->purchase->sum('total_price');
                                $total_purchase +=$purchase;
                                $expense=$project->expense->sum('total_price');
                                $total_expense +=$expense;
                                $purchase_and_expense=$purchase + $expense;
                                $profit_loss=($project->projectPayment->sum('payment_amount') -$purchase_and_expense);

                                $total_total_due +=$profit_loss;
                            @endphp
                            <tr>
                                <td>{{$key+ $projects->firstItem()}}</td>
                                <td >{{$project->companies ?$project->companies->name:''}}</td>
                                <td >{{$project->name}}</td>
                                <td >{{$project->type->name}}</td>
                                <td>{{($project->working_status==1)?'Complete':'Running'}}</td>
                                <td >{{$project->project_value}}</td>
                                <td>{{number_format($project->projectPayment->sum('payment_amount'),2)}}</td>
                                <td>{{number_format($purchase,2)}}</td>
                                <td>{{number_format($expense,2)}}</td>
                                <td>
                                    @if($profit_loss<0)
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-danger">Loss</span>
                                    @else
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-success">Profit</span>
                                    @endif
                                </td>
                                <td class="no-print">
                                    <!-- <a class="btn btn-sm btn-info" id="btn-modal" data-href="{{ action('Backend\Admin\Project\ProjectController@getDetailsModal',$project->id)}}">Details</a> -->
                                    <a class="btn btn-primary btn-sm" href="{{action('Backend\ReportController@projectDetails',$project->id)}}">Details</a>
                                </td>
                            </tr>
                            @endforeach

                            <tr>
                                <th colspan="5"><strong>Total</strong></th>
                                <th><strong>{{number_format($projects->sum('project_value'),2)}}</strong></th>
                                <th><strong>{{number_format($total_payment,2)}}</strong></th>
                                <th><strong>{{number_format($total_purchase,2)}}</strong></th>
                                <th><strong>{{number_format($total_expense,2)}}</strong></th>
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

@endsection
