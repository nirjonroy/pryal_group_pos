@extends('layouts.backend.app')
@section('page_title')
@push('css')
<style>
    .text-right{
        color: red;
    }

    

@media print {
    .table th{
         white-space: nowrap !important;
     }

     .table td {
        white-space: normal !important;
     }

/*

     a[href]:after{
            content:"";
        }
     a[href]:before{
            content:"";
        }*/

    .break_page { page-break-after: always; }



}
   
</style>
@endpush
@section('content')
<div class="" id="print">
    <h4 class="font-weight-bold p-3 mb-0">Daily Satement</h4>
     
    <div class="row" >
        <div class="col-md-12">
            <div class="card p-3">
                 <div class="col-md-12">
                    <form class="no-print">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date From :</label>
                                    <input type="date" name="date_start" class="form-control" value="{{request()->date_start ?request()->date_start:''}}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date To :</label>
                                    <input type="date" name="date_end" class="form-control" value="{{request()->date_end ?request()->date_end:''}}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <br><br><input type="submit"  class="btn btn-primary btn-sm" value="submit">
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                          <div class="form-group col-md-1">
                                <br><br><a class="btn btn-sm btn-primary no-print" onclick="imprimir()">Print</a>
                            </div>

                        </div>
                         <br>
                           
                    </form>
                </div>
                <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>
                
                
<br><br>
                
                
                <div class="card-datatable table-responsive">
                    <h5>Purchase List</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Type</th>
                                <th>Supplier</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                                <td>{{$item->invoice_no}}</td>
                                <td>{{$item->type}}</td>
                                <td>{{$item->suppliers->name}}</td>
                                <td>{{$item->total_price}}</td>
                                <td>{{$item->description}}</td>
                                
                                   <td class="no-print">
                                    
                                   
                                   @if($item->type == 'payment')
                                    <a class="btn btn-primary btn-sm" href="{{ action('PaymentController@supplierPaymentDetails',$item->id)}}"><i class="fa fa-eye"></i></a>
                                    @else
                                     <a class="btn btn-primary btn-sm" href="{{ route('admin.purchase.show',$item->id) }}"><i class="fa fa-eye"></i></a>
                                    @endif

                                <!-- @if(auth()->user()->hasRole('admin'))
                                   @if($item->type == 'payment')
                                    <a class="btn btn-danger btn-sm" 
                                    href="{{ action('PaymentController@supplierPaymentDelete',$item->id)}}" onclick="return confirm(' you want to delete?');">
                                        <i class="fa fa-trash"></i></a>

                                    @else

                                    <a class="btn btn-danger btn-sm" 
                                    href="{{ route('admin.purchase.destroy' , $item->id)}}" onclick="return confirm(' you want to delete?');">
                                        <i class="fa fa-trash"></i></a>

                                    @endif    
                                @endif -->
                                </td> 
                                
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th class="text-right" colspan="5">Total=</th>
                            <th>{{number_format($purchases->sum('total_price'),2)}}</th>
                        </tfoot>
                    </table>
                    <br>


                    <h5>Sell List</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sells as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                                <td>{{$item->invoice_no}}</td>
                                <td>{{$item->type}}</td>
                                <td>{{$item->customer->name}}</td>
                                <td>{{$item->total_price}}</td>
                                <td>{{$item->description}}</td>
                                
                                   <td class="no-print">
                                    
                                   
                                   
                                     <a class="btn btn-primary btn-sm" href="{{ route('sell.show',$item->id) }}"><i class="fa fa-eye"></i></a>
                                   

                                <!-- @if(auth()->user()->hasRole('admin'))
                                   @if($item->type == 'payment')
                                    <a class="btn btn-danger btn-sm" 
                                    href="{{ action('PaymentController@supplierPaymentDelete',$item->id)}}" onclick="return confirm(' you want to delete?');">
                                        <i class="fa fa-trash"></i></a>

                                    @else

                                    <a class="btn btn-danger btn-sm" 
                                    href="{{ route('admin.purchase.destroy' , $item->id)}}" onclick="return confirm(' you want to delete?');">
                                        <i class="fa fa-trash"></i></a>

                                    @endif    
                                @endif -->
                                </td> 
                                
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th class="text-right" colspan="5">Total=</th>
                            <th>{{number_format($sells->sum('total_price'),2)}}</th>
                        </tfoot>
                    </table>

                    <br>
                    <hr style="border-color: red !important;">
                    <br>
                    <h5>Expense List</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th> 
                                <th>Total Amount</th>
                                <th>Description</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($expenses as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ date('d.m.Y',strtotime($item->expense_date)) }}</td>
                                
                                <td>{{ $item->total_price }}</td>
                                <td>{{ $item->description }}</td>
                                <td class="no-print" style="width:10%;">
                                    <div class="btn-group" id="hover-dropdown-demo">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-trigger="hover">Action</button>
                                        <div class="dropdown-menu">
                                            <a class=" btn btn-primary btn-sm" href="{{ route('admin.expense.show',$item->id) }}">View</a>
                                            
                                             @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                                <a class=" btn btn-info btn-sm" href="{{ route('admin.expense.edit',$item->id) }}">Edit</a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin'))
                                             <a class="btn btn-info btn-sm" href="{{ route('admin.expense.edit',$item->id) }}">Edit</a>
                                            @endif
                                            
                                            @if(auth()->user()->hasRole('admin'))
                                            <form action="{{ route('admin.expense.destroy' , $item->id)}}" method="POST">
                                            <input name="_method" type="hidden" value="DELETE">
                                            {{ csrf_field() }}

                                            &nbsp;<input style="margin-top: 10px;" type="submit" value="delete" class="btn btn-sm btn-danger btn-sm" onclick="return confirm(' you want to delete?');">
                                        </form>
                                         @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tr>
                            <td class="text-right" colspan="2">Total=</td>
                            <td>{{number_format($expenses->sum('total_price'),2)}}</td>
                        </tr>
                    </table>
<br><br>
                   
                    <h5>Supplier Payment</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                           <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Purchase Invoice</th>
                                <th>Supplier</th>
                                <th>Payment Amount</th>
                                <th>Payment Method</th>
                                <th>Description of Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($payments as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{  $item->suppliers->name?$item->suppliers->name:'' }}</td>
                                <td>{{  $item->total_price }}</td>
                                <td>{{  $item->method->method }}</td>
                                <td>{{ $item->note }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th class="text-right" colspan="4">Total=</th>
                            <th>{{number_format($payments->sum('total_price'),2)}}</th>
                        </tfoot>
                    </table>

                    <h5>Customer Payment</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                           <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Sell Invoice</th>
                                <th>Customer</th>
                                <th>Payment Amount</th>
                               
                                <th>Description of Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($customer as $item)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{  $item->customer->name?$item->customer->name:'' }}</td>
                                <td>{{  $item->total_price }}</td>
                                
                                <td>{{ $item->note }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th class="text-right" colspan="4">Total=</th>
                            <th>{{number_format($customer->sum('total_price'),2)}}</th>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
@endpush
@endsection
