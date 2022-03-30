@extends('layouts.backend.app')
@section('page_title') 
@push('css')
<style>

</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1 container-p-y"  id="print">
    <h4 class="font-weight-bold py-3 mb-0">Product Wise Report</h4>
    <div class="row">
        
        <div class="col-md-12">
            <div class="card">
               <div class="col-md-12 no-print">
                    <form >
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>From:</label>
                                <input type="date" name="date_start" class="form-control" placeholder="yyyy-mmm-dd" value="{{request()->date_start ?request()->date_start:''}}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>To:</label>
                                <input type="date" name="date_end" class="form-control" placeholder="yyyy-mmm-dd" value="{{request()->date_end ?request()->date_end:''}}">
                            </div>

                            <div class="form-group col-md-2">
                                <label>Products:</label>
                                <select class="form-control" name="product_id" onchange="this.form.submit()">
                                    <option value="" hidden>Select A Product</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                           

                            <div class="form-group col-md-1">
                                <br><br><input type="submit" class="btn btn-primary btn-sm" value="submit">
                            </div>

                             <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>

                             <div class="form-group col-md-1">
                                <br><br><a class="btn btn-info btn-sm" href="{{ action('Backend\ReportController@productWise')}}">Refresh</a>
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

                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Product Unit</th>
                                <!-- <th>Total Product</th> -->
                                <th>Purchase Quantity</th>
                                <th>Purchase Price</th>
                                <th>Sell Quantity</th>
                                <th>Sell Price</th>
                                <th>Total Profit/ Loss</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase_details as $key=> $detail)
                            <tr>
                                <td>{{ $key+ $purchase_details->firstItem() }}</td>
                                <td>{{$detail->name}}</td>
                                <td>{{$detail->u_name}}</td>
                                <!-- <td>{{$detail->product_count}}</td> -->
                                <td>{{$detail->total_quantity}}</td>
                                <td>{{$detail->total_price}}</td>
                                <td>{{$detail->sells_quantity}}</td>
                                <td>{{$detail->sells_total}}</td>
                                <td>{{$detail->sells_total - $detail->total_price}}</td>
                            </tr>
                            @endforeach


                            <tr>
                                <th colspan="2" ><strong></strong></th>
                                <th >
                                    <strong>This Page Summery</strong>
                                </th>
                                <!-- <th >
                                    <strong>{{number_format($purchase_details->sum('product_count'),2)}}</strong>
                                </th> -->
                                <th >
                                    <strong>{{number_format($purchase_details->sum('total_quantity'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($purchase_details->sum('total_price'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_quantity'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_price'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_price') - $purchase_details->sum('total_price'),2)}}</strong>
                                </th>
                                
                            </tr>


                            <tr>
                                <th colspan="2" ><strong></strong></th>
                                <th >
                                    <strong>Summery With Previous Pages</strong>
                                </th>
                                <!-- <th >
                                    <strong>{{number_format($this_page_valus->sum('product_count'),2)}}</strong>
                                </th> -->
                                <th >
                                    <strong>{{number_format($this_page_valus->sum('total_quantity'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($this_page_valus->sum('total_price'),2)}}</strong>
                                </th>
                                
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_quantity'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_price'),2)}}</strong>
                                </th>

                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_price') - $this_page_valus->sum('total_price'),2)}}</strong>
                                </th>

                            </tr>

                            <tr>
                                <th colspan="2" ><strong></strong></th>
                                <th >
                                    <strong>Total  Summery</strong>
                                </th>
                                <!-- <th >
                                    <strong>{{number_format($total_valus->sum('product_count'),2)}}</strong>
                                </th> -->
                                <th >
                                    <strong>{{number_format($total_valus->sum('total_quantity'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('total_price'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_quantity'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_price'),2)}}</strong>
                                </th>
                                <th >
                                    <strong>{{number_format($total_valus->sum('sell_price') - $total_valus->sum('total_price'),2)}}</strong>
                                </th>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <p>{!! urldecode(str_replace("/?","?",$purchase_details->appends(Request::all())->render())) !!}</p>
            </div>
        </div>
    </div>
</div>

@push('js')
@endpush
@endsection
