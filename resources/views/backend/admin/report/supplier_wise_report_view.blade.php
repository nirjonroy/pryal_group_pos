@extends('layouts.backend.app')
@section('page_title')
@push('css')

<style>
.table td, .table th {
    white-space: inherit;
}
</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <div class="row">
       <div class="col-md-12 ">
            <div class="col-md-12 no-print">
                    <form autocomplete="off" >
                        <div class="row">
                              <div class="form-group col-md-3">
                                <label>Date From</label>
                                <input type="date" name="start_date" class="form-control" placeholder="yyyy-mm-dd" value="{{request()->start_date ? request()->start_date :''}}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Date To</label>
                                <input type="date" name="end_date" class="form-control" placeholder="yyyy-mm-dd" value="{{request()->end_date ? request()->end_date:''}}">
                            </div>
                            <div class="form-group col-md-6">
                               <br><br>
                                <input type="submit"  class="btn btn-primary btn-sm" value="SUBMIT">
                               <a class="btn btn-info btn-sm" href="{{ url('reports/supplier-wise-report-view',$supplier->id) }}">Refresh</a>

                             
                                <a class="btn btn-primary btn-sm"  onclick="imprimir()">Print</a>

                                
                               
                                <a class="btn btn-info btn-sm" href="{{url('/reports/supplier-wise-report/get-data-by-day', $supplier->id)}}">Last 1 Month Report</a>
                         

                              
                                <a class="btn btn-info btn-sm" href="{{url('/reports/supplier-wise-report/last-3-month', $supplier->id)}}">Last 3 Month Report</a>
                           
                            

                           <!--  <a class="btn btn-info btn-sm" href="{{url('/admin/supplier/onetimeUpdate/'.$supplier->id)}}">Update</a> -->
                             
                            </div>
                            
                        </div>  
                    </form>
                    
                </div>
               
            <div class="card " >
                        <div class="col-sm-6 ">
                            @include('info.info')
                        </div>
                  
                    <hr class="mb-1">

                    
                        
                   
                    <div class="container">
                         <div class="row" >
                       <div class="col-md-12">
                                <div class="pull-left">
                                    <div class="font-weight-bold mb-2">Supplier :</div>
                                    <div>
                                       <b> Name </b> : {{ $supplier->name }}
                                    </div>
                                    <div>
                                        <b>Address</b> : {{ $supplier->address }}
                                    </div>
                                    <div><b>Phone </b>: {{ $supplier->contract_phone }}
                                    </div>
                                </div>

                         
                                <div class="pull-right" style="margin-top: -80px !important;">
                                    <div class="font-weight-bold mb-2 ">Supplier Payment History:</div>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th class="pr-3">Total Purchase :</th>
                                                <td>
                                                    <strong>
                                                        {{ number_format($supplier->purchase->sum('total_price'),2) }}
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="pr-3">Total Payment:</th>
                                                <td>
                                                    <strong>
                                                        {{ number_format($supplier->purchase_payment->sum('total_price'),2) }}
                                                    </strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="pr-3">Total due:</th>
                                                <td>
                                                    <strong>
                                                       {{ number_format($supplier->purchase->sum('total_price') - $supplier->purchase_payment->sum('total_price'),2) }}
                                                    </strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            
                            </div>
                    </div>
                    </div>
                  

                <div class="card-body">
                    <div class="table-responsive ">
                                <h4>Purchase And Payment  List</h4>
                                <!--  purchase table Start-->
                                <table class="table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">SL</th>
                                                <th style="width: 10%;">Date</th>
                                                <th style="width: 10%;">Type</th>
                                                <th style="width: 10%;">Invoice</th>
                                                <th style="width: 10%;">Company</th>
                                                <th style="width: 10%;">Project</th>
                                                <th style="width: 10%;">Description</th>
                                                <th style="width: 10%;">Purchase</th>
                                                <th style="width: 10%;">Payment</th>
                                                <th style="width: 10%;">Due</th>
                                                <th style="width: 10%;" class="no-print">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             @php
                                                $pur=0;
                                                $pay=0;
                                            @endphp
                                            @foreach ($purchase as $item)

                                            @php
                                                $pur +=$item->type=='purchase' || $item->type=='stock' ? $item->total_price:0;
                                                $pay +=$item->type=='payment' || $item->type=='stock_payment'? $item->total_price:0;
                                            @endphp

                                            <tr>
                                                <td>
                                                    <strong>
                                                        {{ $loop->index+1 }}
                                                    </strong>
                                                </td>

                                                <td>{{ date('d.m.y', strtotime($item->created_at)) }}</td>

                                                <td>{{ $item->type }}</td>
                                                <td>{{ $item->invoice_no }}</td>
                                                <td>{{ $item->companies ?$item->companies->name:'' }}</td>
                                                <td>{{ $item->projects ? $item->projects->name:'' }}</td>
                                                <td >{!! Str::words($item->description, 10) !!}</td>
                                                <td>{{$item->type=='purchase'|| $item->type=='stock'? $item->total_price:'' }}</td>
                                                <td>{{$item->type=='payment' || $item->type=='stock_payment'? $item->total_price:'' }}</td>

                                                <td style="color:red">   {{number_format((float)$item->cash_hand,2)}}
                                                </td>
                                                            
                                                <td class="no-print">
                                                    @if($item->type=='payment')
                                                        <a class="btn btn-primary btn-sm " href="{{ action('PaymentController@supplierPaymentDetails',$item->id)}}">details</a>
                                                    @else
                                                        <a class="btn btn-primary btn-sm " href="{{route('admin.purchase.show',$item->id)}}">details</a>
                                                    @endif    
                                                </td>
    
                                            </tr>
                                            @endforeach
                                        
                                            
                                        </tbody>
                                        <tfoot>
                                            
                                            <tr>
                                                <th colspan="7" style="color:red" class="text-right"> Total=</th>
                                                <th><strong>{{ number_format($pur,2) }}</strong></th>
                                                <th><strong>{{ number_format($pay,2) }}</strong></th>
                                                <th><strong>{{ number_format($pur-$pay,2) }}</strong></th>
                                            </tr>
                                        </tfoot>
                                </table>
                            
                    </div>

                </div>


            </div>
        </div>
    </div>

</div>

@push('js')
<script type="text/javascript">
      $(function(){
        $('.date1, .date2').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            language: "de"
        });
    });
</script>

@endpush
@endsection
