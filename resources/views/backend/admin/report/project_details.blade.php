@extends('layouts.backend.app')
@section('page_title') 
@push('css')
<style type="text/css">
  

.text-right{
      color:red;
  }
  
</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1" id="print">
  <a class="btn btn-primary no-print mt-3 mb-3" onclick="imprimir()">Print</a>
    <div class="row" >
        <div class="col-md-12">
            <div class="card">

                <div class="row">
                    <div class="col-sm-6 pb-4">
                        @include('info.info')
                    </div>
                    
                    <div class="col-sm-6 pb-4">
                        <form>
                            <br>
                            <div class="form-group row">
                                <div class="form-group col-md-4">
                                    <input type="date" class="form-control" name="start" value="{{ request('start') ? request('start'):''}}">
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <input type="date" class="form-control" name="end" value="{{ request('end') ? request('end'):''}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="submit" class="btn btn-sm btn-success" value="SUBMIT">
                                    <a class="btn btn-info btn-sm" href="{{action('Backend\ReportController@projectDetails',$id)}}">Refresh</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="row">
                  <div class="col-md-12">
                    
                    <div class="pull-left">
                        <div class="font-weight-bold mb-2">Company :</div>
                        <div>
                           <b> Name </b> : {{ $project->companies->name }}
                        </div>
                        <div>
                            <b>Address</b> : {{ $project->companies->address }}
                        </div>
                        <div><b>Phone </b>: {{ $project->companies->contract_phone }}
                        </div>
                    </div>

                    
                        <div class="pull-right" style="margin-top:-80px;">
                            <div class="font-weight-bold mb-2 ">Project :</div>
                            <table>
                                <tbody>
                                    <tr>
                                        <th class="pr-3">Project Name :</th>
                                        <td>
                                            <strong>
                                                {{ $project->name }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="pr-3">Project Value:</th>
                                        <td>
                                            <strong>
                                                {{ $project->project_value }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
               
                </div>


                <div class="card-datatable table-responsive">
                  <div class="card-header">
                      <h4>Project Payment Recived List</h4>
                  </div>
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr style="background:#eee">
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Payment Method</th>
                                <th>Receive Amount</th> 
                                <th>Receive Description</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($payments as $payment)
                           <tr>
                               <td>{{ $loop->index+1 }}</td>
                               <td>{{date('d.m.Y', strtotime($payment->created_at))}}</td>
                               <td>{{$payment->method->method}}</td>
                               <td>{{$payment->payment_amount}}</td>
                               <td>{{$payment->note}}</td>
                           </tr>
                           @endforeach
                           <tr>
                             <th colspan="3" class="text-right">Total=</th>
                             <th>{{number_format($payments->sum('payment_amount'),2)}}</th>
                           </tr>
                        </tbody>
                    </table>
                </div>






                <div class="card-datatable table-responsive">
                  <div class="card-header">
                      <h4>Project Purchase List</h4>
                  </div>
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr style="background:#eee">
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Invoice</th> 
                                <th>Supplier</th> 
                                <th>Purchase Amount</th> 
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($purchase as $pur)
                           <tr>
                               <td>{{ $loop->index+1 }}</td>
                               <td>{{date('d.m.Y', strtotime($pur->created_at))}}</td>
                               <td>{{$pur->invoice_no}}</td>
                               <td>{{$pur->suppliers->name}}</td>
                               <td>{{$pur->total_price}}</td>
                               <td>{{$pur->description}}</td>
                           </tr>
                           @endforeach
                        </tbody>

                        <tr>
                           <th colspan="4" class="text-right">Total=</th>
                           <th>{{number_format($purchase->sum('total_price'),2)}}</th>
                        </tr>
                    </table>
                </div>



            <div class="card-datatable table-responsive">
                  <div class="card-header">
                      <h4>Project Expense List</h4>
                  </div>
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr style="background:#eee">
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Invoice</th> 
                                <th>Expense Amount</th> 
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($expense as $exp)
                           <tr>
                               <td>{{ $loop->index+1 }}</td>
                               <td>{{date('d.m.Y', strtotime($exp->expense_date))}}</td>
                               <td>{{$exp->invoice_no}}</td>
                               <td>{{$exp->total_price}}</td>
                               <td>{{$exp->description}}</td>
                           </tr>
                           @endforeach
                        </tbody>

                        <tr>
                           <th colspan="3" class="text-right">Total =</th>
                           <th>{{number_format($project->expense->sum('total_price'),2)}}</th>
                        </tr>
                        
                        
                    </table>
                </div>


                <div class="card-datatable table-responsive">
                  <div class="card-header">
                      <h4>Stock Transfer List</h4>
                  </div>
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr style="background:#eee">
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Invoice</th> 
                                <th>Store</th> 
                                <th>Company</th> 
                                <th>Transfer Amount</th> 
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($sells as $sell)
                           <tr>
                               <td>{{ $loop->index+1 }}</td>
                               <td>{{date('d.m.Y', strtotime($sell->created_at))}}</td>
                               <td>{{$sell->invoice_no}}</td>
                               <td>{{$sell->store->name}}</td>
                               <td>{{$sell->companies->name}}</td>
                               <td>{{$sell->total_price}}</td>
                               <td>{{$sell->description}}</td>
                           </tr>
                           @endforeach
                        </tbody>

                        <tr>
                           <th colspan="5" class="text-right">Total=</th>
                           <th>{{number_format($sells->sum('total_price'),2)}}</th>
                        </tr>
                    </table>
                </div>

                <div class="card-datatable table-responsive">
                  <div class="card-header">
                      <h4>Stock  Return List</h4>
                  </div>
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr style="background:#eee">
                                <th>SL</th>
                                <th>Date</th> 
                                <th>Invoice</th> 
                                <th>Store</th> 
                                <th>Company</th> 
                                <th>Amount</th> 
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($sell_returns as $sell)
                           <tr>
                               <td>{{ $loop->index+1 }}</td>
                               <td>{{date('d.m.Y', strtotime($sell->created_at))}}</td>
                               <td>{{$sell->invoice_no}}</td>
                               <td>{{$sell->store->name}}</td>
                               <td>{{$sell->companies->name}}</td>
                               <td>{{$sell->total_price}}</td>
                               <td>{{$sell->description}}</td>
                           </tr>
                           @endforeach
                        </tbody>

                        <tr>
                           <th colspan="5" class="text-right">Total=</th>
                           <th>{{number_format($sell_returns->sum('total_price'),2)}}</th>
                        </tr>
                    </table>
                </div>
                


                <div class="card-datatable table-responsive">
                  
                    <table class="datatables-demo table table-striped table-bordered">
                        
                        
                        <tr>
                           <th colspan="4" class="text-right">Total Project Payment  =</th>
                           <th>
                                {{number_format($payments->sum('payment_amount'),2)}}
                           </th>
                        </tr>
                        
                        <tr>
                           <th colspan="4" class="text-right">Total Purcase And Expenses And Transfer =</th>
                           <th>
                                {{number_format($purchase->sum('total_price') + $expense->sum('total_price') + $sells->sum('total_price'),2)}}
                           </th>
                        </tr>
                        
                         <tr>
                           <th colspan="4" class="text-right">Total Stock Return =</th>
                           <th>
                               {{number_format($sell_returns->sum('total_price'),2)}}
                           </th>
                        </tr>
                        
                        <tr>
                            <th colspan="4" class="text-right"> Profit / Loss = </th>
                            <th>
                                
                                 
                                
                            
                                {{number_format(($payments->sum('payment_amount')) - (($purchase->sum('total_price') +$expense->sum('total_price') + $sells->sum('total_price'))) + ($sell_returns->sum('total_price')),2)}}
                               
                               
                            </th>
                        </tr>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>

@push('js')
@endpush
@endsection
