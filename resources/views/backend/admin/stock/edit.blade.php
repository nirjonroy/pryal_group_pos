@extends('layouts.backend.app')
@section('page_title') | Stock Purchase Update @endsection
@push('css')
<style>
    .red{color:red;}
    .gray{color:gray;}
</style>
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Update Stock Purchase</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.purchase.index') }}">Purchase List</a></li>
        </ol>
    </div>

    <form action="{{ route('admin.stocks.update',$purchase->id) }}" method="POST">
    @method('PUT')
    @csrf
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="supplier">Supplier Name :</label>
                    <select name="supplier_id"  class="form-control select2" required>
                        <option value="">Select One</option>
                        @foreach ($suppliers as $item)
                        <option {{ $purchase->supplier_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('supplier_id'))
                    <span class="red" role="alert">
                        <strong>{{ $errors->first('supplier_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="Project">Store :</label>
                    <select name="store_id"  class="form-control select2" required>
                        <option value="" hidden>Store Select</option>
                        @foreach($stores as $store)
                        <option value="{{$store->id}}" {{ $purchase->store_id == $store->id ? 'selected' : 'disabled' }}>{{$store->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('store_id'))
                    <span class="red" role="alert">
                        <strong>{{ $errors->first('store_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">

            <div class="card mb-4">
                @if(auth()->user()->hasRole('admin'))
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" class="form-control" required value="{{date('Y-m-d', strtotime($purchase->created_at))}}">
                </div>
                @else
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" class="form-control" required value="{{date('Y-m-d', strtotime($purchase->created_at))}}" readonly>
                </div>
                @endif

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h6 class="card-header">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Product :</label>
                            <select  id="product_id" class="form-control select2">
                                <option value="">Select One</option>
                                @foreach ($products as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        
                    </div>
                </h6>
                <div class="card-body">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Product unit</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>

                        @foreach ($purchase->purchaseDetails as $key => $item)
                                <tr>
                                    <td><label class="form-check-label">#{{ $key+1 }}</label></td>
                                    <td>
                                        {{ $item->products->name }}
                                        <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                        <input type="hidden" name="details_id[]" value="{{ $item->id }}">
                                    </td>
                                    
                                    <td>{{ $item->products->unit->name }}</td>
                                    <td>
                                        <input name="quantity[]" id="qty-{{ $item->product_id }}" data-unit_price="{{ $item->unit_price }}" value="{{ $item->quantity }}" type="number" step="any" class="changeToGet col-xl-8 col-lg-8 col-12 form-control">
                                    </td>
                                    <td>
                                        <input name="sale_unit_price[]" value="{{ $item->unit_price }}" type="number"  step="any"  id="utp-{{ $item->product_id }}" class="old_sale_unit_price col-xl-8 col-lg-8 col-12 form-control">
                                    </td>
                                    <td>
                                        <span id="set-{{ $item->product_id }}" class="old_sum">
                                            {{ $item->quantity * $item->unit_price }}
                                        </span>
                                    </td>
                                    <td style="width: 10%">
                                        <a  id="DeleteButton" class="dropdown-item btn btn-sm btn-danger" style="width:100%;text-align: center;"><i class="fas fa-times text-orange-red"></i></a>
                                    </td>
                                </tr>
                        @endforeach
                        <tbody id="showResult">
                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="2">
                                    <strong id="totalQty">{{$purchase->total_quantity}}</strong>
                                    <input type="hidden" name="totalProductQuantity" id="totalProductQuantity" value="{{$purchase->total_quantity}}">
                                </th>
                                <th>
                                    <strong id="totalAmount">{{$purchase->total_price}}</strong>
                                    <input type="hidden" id="totalProductPrice" name="totalProductPrice" value="{{$purchase->total_price}}">
                                </th>
                                <th>
                                    <a href="{{ route('admin.addToCartProductRemoveAll') }}" class="btn btn-primary">Remove Cart</a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <h3> Service Cost</h3>
                    <div class="row" style="margin-bottom: 5%;margin-top:6%;">
                        
                        <div class="col-md-3">
                            <label for="">Bosta Cost</label>
                            <input type="number" name="bosta_cost" id="bosta_cost" value="{{$purchase->bosta_cost}}" class="form-control" >
                        </div>

                        <div class="col-md-3">
                            <label for="">Batch No </label>
                            <input type="number" name="batch_no" value="{{$purchase->batch_no}}" class="form-control" >
                        </div>

                        <div class="col-md-3">
                            <label for="">Car Rent </label>
                            <input type="number" name="car_rent" id="car_rent" value="{{$purchase->car_rent}}" class="form-control" >
                        </div>
                        
                        <div class="col-md-3">
                            <label for=""> Labour Cost  </label>
                            <input type="number" name="labour_cost" id="labour_cost" value="{{$purchase->labour_cost}}" class="form-control" >
                        </div>

                        
                    </div>
                    
                    <div> 
                        <h3> Payments</h3>
                            <table class="table table-bordered">
                                <tr>
                                    <td> SL </td>
                                    <td> Date </td>
                                    <td> Payment </td>
                                    <td> Payment Note </td>
                                    
                                </tr>
                                @foreach($stock_pay as $st)
                                <tr> 
                                    <td>1 </td>
                                    <td> {{date('d.m.Y', strtotime($st->created_at))}} </td>
                                    <td> <input type="number" value="{{$st->total_price}}" class="form-control">  </td>
                                    <td> <textarea class="form-control" name="description" placeholder="Enter Note Here..">{{$st->description}}</textarea> </td>
                                </tr>
                                @endforeach
                            </table>
                        
                    </div>
                    
                    
                    <div class="row" style="margin-bottom: 5%;margin-top:6%;">
                        <div class="col-md-4">
                            <label for="">Payment Method</label>
                            <select name="payment_method_id" id="" class="form-control">
                                @foreach ($payment_methods as $item)
                                <option  {{ $purchase->payment_method_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="">Payment Amount</label>
                            <input type="number" step="any" name="payment_amount" id="" class="form-control" value="{{$stock_pay->sum('total_price')}}">
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="">Note :</label>
                            <textarea class="form-control" name="description" placeholder="Enter Note Here..">{{$purchase->description}}</textarea>
                        </div>
                        
                    </div>
                    
                    
                    
                    <input id="submit" type="submit" class="btn btn-info pull-right" value="Update Now">
                </div>
            </div>
        </div>
    </div>
</form>

</div>

<div id="getUrl" data-url="{{route('admin.addToCartProduct')}}"></div>
<div id="addToCartProductDefaultLoading" data-url="{{route('admin.addToCartProductDefaultLoading')}}"></div>
<div id="addToCartProductUpdateQtyPrice" data-url="{{route('admin.addToCartProductUpdateQtyPrice')}}"></div>
<div id="addToCartProductUpdateQtyPrice" data-url="{{route('admin.addToCartProductUpdateQtyPrice')}}"></div>
<div id="addToCartProductUpdateQtyPrice" data-url="{{route('admin.addToCartProductUpdateQtyPrice')}}"></div>
@push('js')
<script>
    function calculation(){
        let total_qty = 0;
        $('.clickToGet, .changeToGet').each(function()
        {
            total_qty += parseFloat($(this).val());
        })
        if(isNaN(total_qty)) {
            total_qty = 0;
        }
        $('#totalQty').text(total_qty);
        $('#totalProductQuantity').val(total_qty);

        let total = 0;
        $('.sum, .old_sum').each(function()
        {
            total += parseFloat($(this).text());
        })
        let toalAmount = (parseFloat(total));
        toalAmount = (toalAmount.toFixed(2));
        $('#totalAmount').text(toalAmount);
        $('#totalProductPrice').val(toalAmount);

        var totalProductAmount =  $('#totalProductPrice').val();
        if(totalProductAmount > 0)
        {
            $('#submit').removeAttr('disabled','disabled');
        }else{
            $('#submit').attr('disabled','disabled');
        }
    }
    
    function old_calculation(){
        let total_qty = 0;
        $('.clickToGet, .changeToGet').each(function()
        {
            total_qty += parseFloat($(this).val());
        })
        
        if(isNaN(total_qty)) {
            total_qty = 0;
        }
        $('#totalQty').text(total_qty);
        $('#totalProductQuantity').val(total_qty);

        let total = 0;
        $('.old_sum').each(function()
        {
            total += parseFloat($(this).text());
        })
        
        let toalAmount = (parseFloat(total));
        toalAmount = (toalAmount.toFixed(2));
        $('#totalAmount').text(toalAmount);
        $('#totalProductPrice').val(toalAmount);

        var totalProductAmount =  $('#totalProductPrice').val();
        if(totalProductAmount > 0)
        {
            $('#submit').removeAttr('disabled','disabled');
        }else{
            $('#submit').attr('disabled','disabled');
        }
    }
    
</script>


<script>
    $(document).ready(function(){
        $(document).on('blur','#paymentAmount',function(){
            var paymentAmount = $(this).val();
            var totalProductAmount =  $('#totalProductPrice').val();
            if(totalProductAmount == paymentAmount || totalProductAmount > paymentAmount)
            {
                $('#paymentAmount').css({
                    'background-color':'green',
                    'color':'yellow',
                    'font-size':'18px'
                });
            }
            else{
                //$('#paymentAmount').val('');
                $('#paymentAmount').css({
                    'background-color':'red',
                    'color':'white'
                });
                //swal("Input Wrong!", "Your Total Purchase Amount is "+totalProductAmount+" Tk", "error");
            }
        });

        //===========================
    });
</script>

<script>
    $(document).ready(function(){


        /*== sale add to cart end==*/
        $('#product_id').on('change',function(e){
            e.preventDefault();
                let product_id = $('#product_id').val();
                let url = $('#getUrl').data('url');
                    $.ajax({
                        url:url,
                        type:'GET',
                        datatype:'html',
                        cache : false,
                        async: false,
                        data:{product_id},
                        success:function(response)
                        {
                            if(response.status)
                            {
                                $('#showResult').html(response.data);
                            }else{
                                return;
                            }
                            calculation();
                        },
                    });
                });
            /*== sale add to cart end==*/

            /*==sale remove single from add to cart==*/
            $(document).on('click', '.remove_single_sale_cart' ,function(eee){
                eee.preventDefault();
                let url = $(this).data('url');
                let product_id = $(this).data('id');
                $.ajax({
                    url:url,
                    type:'GET',
                    datatype:'html',
                    data:{product_id:product_id},
                    success:function(response)
                    {
                        if(response.status)
                        {
                            $('#showResult').html(response.data);
                        }else{
                            return;
                        }
                        calculation();
                    },
                });
            });
            /*==sale remove single from add to cart==*/

    });
</script>

<script>
        /*== sale add to cart Update qty and price start==*/
        $(document).on('blur','.clickToGet, .sale_unit_price', function(ee){
        ee.preventDefault();
        let id = parseInt($(this).attr("id").substr(4));
        let qty = $('#qty-' + id).val();
        let unit_price = $('#utp-' + id).val();
        setTimeout(function (){
        let url = $('#addToCartProductUpdateQtyPrice').data('url');
                $.ajax({
                    url:url,
                    type:'GET',
                    datatype:'html',
                    cache : false,
                    async: false,
                    data:{id,qty,unit_price},
                    success:function(response)
                    {
                        if(response.status)
                        {
                            $('#showResult').html(response.data);
                        }else{
                            return;
                        }
                        calculation();
                    },
                });
            }, 1000)
        });

    /*== sale add to cart Update qty and price end==*/
</script>


<script>
    // not using
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    
    $(document).on("click", "#DeleteButton", function(e) {
        e.preventDefault(e);
         $(this).closest("tr").remove();
         calculation();
    });
    
    $(document).on('blur','.changeToGet, .old_sale_unit_price',function(){
        
        let id = parseInt($(this).attr("id").substr(4));
        let qty = $('#qty-' + id).val();
        let unit_price = $('#utp-' + id).val();
        $('#set-' + id).text((qty*unit_price).toFixed(2));
        
        
        old_calculation();
    });
</script>
@endpush
@endsection
