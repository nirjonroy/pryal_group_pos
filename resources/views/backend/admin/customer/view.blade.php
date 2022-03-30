@extends('layouts.backend.app')
@section('page_title')
@push('css')

<style>

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
                               

                             
                                <a class="btn btn-primary btn-sm"  onclick="imprimir()">Print</a>

                                
                               
                                
                          
                            

                         
                             
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
                                    <div class="font-weight-bold mb-2">Customer :</div>
                                    <div>
                                       <b> Name </b> : {{ $customer->name }}
                                    </div>
                                    <div>
                                        <b>Address</b> : {{ $customer->address }}
                                    </div>
                                    <div><b>Phone </b>: {{ $customer->contract_phone }}
                                    </div>
                                </div>

                         
                                <div class="pull-right" style="margin-top: -80px !important;">
                                    <div class="font-weight-bold mb-2 ">Customer Payment History:</div>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th class="pr-3">Total Purchase :</th>
                                                <td>
                                                    <strong>
                                                        {{ number_format($customer->sell->sum('total_price'),2) }}
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="pr-3">Total Payment:</th>
                                                <td>
                                                    <strong>
                                                        {{ number_format($customer->sell_payment->sum('total_price'),2) }}
                                                    </strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th class="pr-3">Total due:</th>
                                                <td>
                                                    <strong>
                                                       {{ number_format($customer->sell->sum('total_price') - $customer->sell_payment->sum('total_price'),2) }}
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
                    <div class="table-responsive mb-4">
                                <h4>Sell And Payment  List</h4>
                                <!--  purchase table Start-->
                                <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Store</th>
                                                <th>Total Amount</th>
                                                <th>Total Payment</th>
                                                <th>Total Due</th>
                                                <th>Status</th>
                                                <th class="no-print">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             @php
                                                $pur=0;
                                                $pay=0;
                                            @endphp
                                            @foreach ($sell as $item)

                                            @php
                                                $pur +=$item->total_price;
                                                $pay +=$item->payments->sum('total_price');
                                            @endphp

                                            <tr>
                                                <td>
                                                    <strong>
                                                        {{ $loop->index+1 }}
                                                    </strong>
                                                </td>

                                                <td>{{ date('d.m.y', strtotime($item->created_at)) }}</td>

                                                
                                                <td>{{ $item->invoice_no }}</td>
                                                <td>dd</td>
                                                <td>{{ $item->total_price }}</td>
                                                <td>{{$item->payments->sum('total_price')}}</td>
                                                <td>{{$item->total_price - $item->payments->sum('total_price')}}</td>
                                                

                                                <td style="color:red">   {{ $item->status}}
                                                </td>

                                                <td class="no-print"><a class="btn btn-primary btn-sm " href="">details</a></td>
    
                                            </tr>
                                            @endforeach
                                        
                                            
                                        </tbody>
                                        <tfoot>
                                            
                                            <tr>
                                                <th colspan="4" style="color:red" class="text-right"> Total=</th>
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
