@extends('layouts.backend.app')
@section('page_title') 
@push('css')
<style>

</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1" id="print">
    <h4 class="font-weight-bold py-3 mb-0">Complete Yearly Project Report</h4>
 
                                 
    <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                             
    <a class="btn btn-sm btn-primary no-print" onclick="imprimir()">print</a><br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>
                <div class="table-responsive" >
                    <table class=" table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th width="20px;">Total Number Of Complete Project</th>
                                <th>Total Project Value</th>
                                <th>Total Receive Amount</th>
                                <th>Total Purchase Amount</th>
                                <th>Total Expense Amount</th>
                                <th>Profit / Loss</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                             $total_recive = 0;
                             $total_purchase = 0;
                             $total_expense = 0;
                             $total_profit_loss = 0;

                            @endphp
                            @foreach($data as $key=> $item)
                            @php
                                $p=DB::table('projects')->where('working_status',1)
                                        ->whereYear('date',$item->year)
                                        ->pluck('id')
                                        ->toArray();


                                $purchase=DB::table('purchases')->where('type','purchase')
                                            ->join('projects','purchases.project_id','=','projects.id')
                                            ->whereIn('projects.id',$p)
                                            ->sum('purchases.total_price');

                                 $expense=DB::table('expenses')
                                            ->join('projects','expenses.project_id','=','projects.id')
                                            ->whereIn('projects.id',$p)
                                            ->sum('expenses.total_price');

                                 $receive=DB::table('project_payment_histories as pph')
                                            ->join('projects','pph.project_id','=','projects.id')
                                            ->whereIn('projects.id',$p)
                                            ->sum('pph.payment_amount');



                                $purchase_and_expense=$purchase + $expense;
                                $profit_loss=($receive - $purchase_and_expense);

                               $total_recive+= $receive;
                               $total_purchase+= $purchase;
                               $total_expense+= $expense;
                               $total_profit_loss+= $profit_loss;
                            @endphp
                            <tr>
                                <td>{{$item->year}}</td>
                                <td>{{$item->data}}</td>
                                <td>{{$item->amount}}</td>
                                <td>{{$receive}}</td>
                                <td>{{$purchase}}</td>
                                <td>{{$expense}}</td>

                                <td>
                                    @if($profit_loss<0)
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-danger">Loss</span>
                                    @else
                                        {{number_format($profit_loss,2)}}<span class="badge badge-pill badge-success">Profit</span>
                                    @endif
                                </td>

                                <td class="no-print">
                                    <a class="btn btn-info btn-sm" href="{{action('Backend\ReportController@yearWiseProject',$item->year)}}">Details</a>
                                </td>
                            </tr>

                         
                            @endforeach
                             <tr>
                                <th colspan="1"><strong>Total</strong></th>
                                <th><strong>{{number_format($data->sum('data'),2)}}</strong></th>
                                <th><strong>{{number_format($data->sum('amount'),2)}}</strong></th>
                                 <th><strong>{{number_format($total_recive,2)}}</strong></th>
                                 <th><strong>{{number_format($total_purchase,2)}}</strong></th>
                                 <th><strong>{{number_format($total_expense,2)}}</strong></th>


                                 @if($total_profit_loss<0)
                                  <th><strong>{{number_format($total_profit_loss,2)}}<span class="badge badge-pill badge-danger">Loss</span></strong></th>
                                 @else
                                   <th><strong>{{number_format($total_profit_loss,2)}}<span class="badge badge-pill badge-success">Profit</span></strong></th>
                                 @endif
                                 
                              
                            </tr>


                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
