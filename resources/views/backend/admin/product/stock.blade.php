@extends('layouts.backend.app')
@section('page_title','| Product Index') 
@push('css')
<style>

</style>
<link rel="stylesheet" href="{{ asset('backend/links') }}/assets/libs/datatables/datatables.css">
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0 no-print">Product</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.product.create') }}">Product Stock</a></li>
        </ol>
    </div>

<div class="form-group col-md-1">
    
<br><br><a class="btn btn-sm btn-primary no-print" onclick="imprimir()">Print</a>
</div>
<div class="form-group ">
      <br><br>
     <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
   
</div>



    <div class="row" >
        <div class="col-md-12">
            
            
            <div class="card">
                <h6 class="card-header">Product List</h6>
                <div class="col-sm-6 pb-4">
                    @include('info.info')
                    
                    <a href="{{route('product-stock')}}" class="btn btn-success" >Get All Data</a>
                </div>
                
                <div class="col-sm-6 pb-4 text-right"> 
                <form> 
                
                    <select class="form-control" aria-label="Default select example" onchange="this.form.submit()" name="store_id">
                      <option value="" hidden>Select Store</option>
                      @foreach($productByStock as $probys)
                      
                      <option value="{{$probys->id}}" {{request('store_id')==$probys->id ? 'selected':''}}>{{$probys->name}}</option>
                      @endforeach
                    </select>
                </form>
                </div>
                <div class="table-responsive">
                    <table class=" table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Product Unit</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Total Purchase Price</th>
                                <th>Total Sell Price</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock as $key => $item)
                            <tr>
                                
                                <td>{{ $loop->index+1 }}</td>
                                
                                <td>
                                    {{ $item->name }}
                                </td>
                                 <td>{{$item->uname}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->unit_price}}</td>
                                <td>{{number_format($item->total_purchase_price,2)}}</td>
                                <td>{{number_format($item->total_sell_price,2)}}</td>
                            </tr>
                            @endforeach
                            <tr style="background:gray; color:white"> 
                                <th colspan="3">Total</th>
                                <th colspan="2">{{number_format($stock->sum('qty'),2)}}</th>
                                <th colspan="1">{{number_format($stock->sum('total_purchase_price'),2)}}</th>
                                <th>{{number_format($stock->sum('total_sell_price'),2)}}</th>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!--########################################################################-->
<!--########################################################################-->
<!---main content page end div-->
</div>
<!---main content page end div-->
<!--########################################################################-->
<!--########################################################################-->

@push('js')
<script src="{{ asset('backend/links')}}/assets/libs/datatables/datatables.js"></script>
<script src="{{ asset('backend/links')}}/assets/js/pages/tables_datatables.js"></script>
@endpush
@endsection
