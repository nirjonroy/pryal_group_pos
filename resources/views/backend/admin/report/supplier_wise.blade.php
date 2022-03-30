@extends('layouts.backend.app')
@section('page_title') | Supplier Wise Report @endsection

@section('content')
<div class="container-fluid" id="print">
    @include('layouts.backend.partial.success_error_status_message')

    <h4 class="font-weight-bold py-3 mb-0">Supplier Report</h4>
   
        <div class="col-md-12">
            <div class="card">
                <div class="card-header no-print" >
                    <form>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                    <option value="" hidden>Select</option>
                                    <option value="alldata" {{(request()->alldata=='alldata')?'selected':''}}>Get All Data</option>
                                    
                                </select>
                            </div>
                        
                            <div class="form-group col-md-4">
                                <label>Suppliers Type:</label>
                                <select class="form-control" name="type_id" onchange="this.form.submit()">
                                    <option value="" hidden>Select A Status</option>
                                    @foreach($types as $type)
                                    <option value="{{$type->id}}" {{(request()->type_id==$type->id)?'selected':''}}>{{$type->name}}</option>
                                    @endforeach
                                
                                    <option value="" {{(request()->type_id=='')?'selected':''}}>All</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Suppliers:</label>
                                <select class="form-control" name="supplier_id" onchange="this.form.submit()">
                                    <option value="" {{request()->supplier_id=='' ?'selected':''}}>All</option>
                                    @foreach($sups as $sup)
                                    <option value="{{$sup->id}}" {{request()->supplier_id==$sup->id ?'selected':''}}>{{$sup->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                              
                                <br><br><a class="btn btn-sm btn-primary" onclick="imprimir()">Print</a>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>
                <div class="card-datatable table-responsive" >
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Supplier Name</th>
                                
                                <th>Total Purchase</th>
                                <th>Total Payment</th>
                                <th>Total Due</th>
                                <!--<th class="no-print">Action</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_purchase=0;
                                $total_purchase_pay=0;
                                $total_due=0;
                            @endphp
                            @foreach ($suppliers as $item)

                            @php
                                $total_purchase+=$item->stockPurchase->sum('total_price');
                                $total_purchase_pay+=$item->purchaseStockpayment->sum('total_price');
                                $total_due+=$item->stockPurchase->sum('total_price') - $item->purchaseStockpayment->sum('total_price');
                            @endphp
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>
                                    {{$item->name}}
                                </td>
                               
                                
                                <td>{{$item->stockPurchase->sum('total_price')}}</td>
                                <td>{{$item->purchaseStockpayment->sum('total_price')}}</td>
                                <td>{{number_format($item->stockPurchase->sum('total_price') - $item->purchaseStockpayment->sum('total_price'),2)}}</td>
                                
                            </tr>
                            @endforeach
                            

                            @php
                                $total_purchase_amount=0;
                                $total_purchase_pay_amount=0;
                                $total_due_amount=0;
                            @endphp
                            @foreach ($total_suppliers as $item)

                            @php
                                $total_purchase_amount+=$item->stockPurchase->sum('total_price');
                                $total_purchase_pay_amount+=$item->purchaseStockpayment->sum('total_price');
                                $total_due_amount+=$item->stockPurchase->sum('total_price') - $item->purchaseStockpayment->sum('total_price');
                            @endphp

                            @endforeach

                            <tr>
                                <th colspan="1"></th>
                                <th ><strong>Total Summery</strong></th>
                                 <th><strong>{{number_format($total_purchase_amount,2)}}</strong></th>
                                <th><strong>{{number_format($total_purchase_pay_amount,2)}}</strong></th>
                                <th><strong>{{number_format($total_due_amount,2)}}</strong></th>
                            </tr>

                        </tbody>
                    </table>
                </div>
        <p class="no-print">{!! urldecode(str_replace("/?","?",$suppliers->appends(Request::all())->render())) !!}</p>
            </div>
        </div>
    </div>

</div>
@push('js')
@endpush
@endsection
