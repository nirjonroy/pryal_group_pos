@extends('layouts.backend.app')
@section('page_title') | Stock Return Update @endsection
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

    <h4 class="font-weight-bold py-3 mb-0">Update Stock Return</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            
        </ol>
    </div>

    <form action="{{ route('stock_returns.update',$sell->id) }}" method="POST">
    @method('PUT')
    @csrf
    <div class="row">

        

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="Project">Store :</label>
                    <select name="store_id"  class="form-control" required>
                        <option value="" hidden>Store Select</option>
                        @foreach($stores as $store)
                        <option {{ $sell->store_id == $store->id ? 'selected' : 'disabled' }} value="{{$store->id}}">{{$store->name}}</option>
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
                    <input type="date" name="date" class="form-control" required value="{{date('Y-m-d', strtotime($sell->created_at))}}">
                </div>
                @else
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" class="form-control" required value="{{date('Y-m-d', strtotime($sell->created_at))}}" readonly>
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
                                @foreach ($product as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="">Note :</label>
                            <textarea class="form-control" name="description" placeholder="Enter Note Here..">{{$sell->description}}</textarea>
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

                        @foreach ($sell->sellDetails as $key => $item)
                                @php
                                    $available_qty=($item->quantity+getSellStock($item->product_id,$sell->store_id));
                                @endphp

                                <tr>
                                    <td><label class="form-check-label">#{{ $key+1 }}</label></td>
                                    <td>
                                        {{ $item->products->name }}
                                        <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                        <input type="hidden" name="details_id[]" value="{{ $item->id }}">
                                    </td>
                                    
                                    <td>{{ $item->products->unit->name }}</td>
                                    <td>
                                        <input name="quantity[]" id="qty-{{ $item->product_id }}" data-unit_price="{{ $item->unit_price }}" value="{{ $item->quantity }}" type="number" step="any" class="changeToGet col-xl-8 col-lg-8 col-12 form-control" data-qty="{{$available_qty}}">
                                    </td>
                                    <td>
                                        <input name="sale_unit_price[]" value="{{ $item->unit_price }}" type="number"  step="any"  id="utp-{{ $item->product_id }}" class="old_sale_unit_price col-xl-8 col-lg-8 col-12 form-control">
                                    </td>
                                    <td>
                                        <span id="set-{{ $item->product_id }}" class="old_sum">
                                            {{ $item->sell->total_price }}
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
                                    <strong id="totalQty">{{$sell->total_quantity}}</strong>
                                    <input type="hidden" name="totalProductQuantity" id="totalProductQuantity" value="{{$sell->total_quantity}}">
                                </th>
                                <th>
                                    <strong id="totalAmount">{{$sell->total_price}}</strong>
                                    <input type="hidden" id="totalProductPrice" name="totalProductPrice" value="{{$sell->total_price}}">
                                </th>
                                
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row" style="margin-bottom: 5%;margin-top:10%;">
                        <div class="col-md-6">
                            <label for="">Payment Method</label>
                            <select name="payment_method_id" id="" class="form-control">
                                @foreach ($payment_methods as $item)
                                <option  {{ $sell->payment_method_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Payment Amount</label>
                            <input type="number" step="any" name="payment_amount" id="paymentAmount" class="form-control" value="0" readonly>
                        </div>
                    </div>
                    <input id="submit" type="submit" class="btn btn-info pull-right" value="Update Now">
                </div>
            </div>
        </div>
    </div>
</form>

</div>

@push('js')

<!-------------Calculation------------>
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
        $('.old_sum').each(function()
        {
            total += parseFloat($(this).text());
        })

        $('.sum').each(function()
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
<!-------------Calculation------------>

<script>
    $(document).ready(function(){
        
    });
</script>

<script>
    $(document).ready(function(){

        //default loading
        var totalProductAmount =  $('#totalProductPrice').val();
        if(totalProductAmount > 0)
        {
            $('#submit').removeAttr('disabled','disabled');
        }else{
            $('#submit').attr('disabled','disabled');
        }
        //default loading


        $.ajax({
            url:"{{ route('stock_returns.create')}}",
            type:'GET',
            datatype:'html',
            cache : false,
            async: false,
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
        //default loading


        /*== sale add to cart end==*/
        $('#product_id').on('change',function(e){
            e.preventDefault();
                let product_id = $('#product_id').val();
                let store_id =$("select[name='store_id']").find('option:selected').val();
                
                if ((product_id =="") || (store_id =="")) {

                    swal('warning','Select Project First','error');
                    return false;
                }
            
            $.ajax({
                url:"{{ action('StockReturnController@getProduct')}}",
                type:'GET',
                data:{product_id,store_id},
                success:function(response)
                {
                    console.log(response);
                    if(response.status==true)
                    {
                        $('#showResult').html(response.data);
                    }else if(response.status==false){
                        swal('warning',response.msg,'error');
                    }
                    calculation();
                },
                error: function (error) {
                    swal('warning','Not Found in Store Select another Store','error');
                }
            });
        });
            /*== sale add to cart end==*/

            /*==sale remove single from add to cart==*/
            $(document).on('click', '.remove_single_sale_cart' ,function(eee){
                eee.preventDefault();

                let product_id = $(this).data('id');
                $.ajax({
                    url:"{{ action('StockReturnController@removeSingleCart')}}",
                    type:'GET',
                    datatype:'html',
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
            /*==sale remove single from add to cart==*/

    });
</script>

<script>
        /*== sale add to cart Update qty and price start==*/
        $(document).on('blur','.sale_unit_price', function(){
     
        let id = parseInt($(this).attr("id").substr(4));
        let qty_input = $('#qty-' + id);
        let unit_price_input = $('#utp-' + id);
        let qty = qty_input.val();
        let unit_price = unit_price_input.val();
        let store_id =$("select[name='store_id']").find('option:selected').val();

        setTimeout(function (){
        if (unit_price_input.is(":focus") || qty_input.is(":focus")) {
            return;
        }
            $.ajax({
                url:"{{ action('StockReturnController@cartUpdate')}}",
                type:'GET',
                datatype:'html',
                cache : false,
                async: false,
                data:{id,qty,unit_price,store_id},
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
        }, 500);
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
</script>

<script type="text/javascript">
    $(document).ready(function() {
        
    $('select[name="company_id"]').on('change click', function() {
        $('#form_section').html('');
        var catID = $(this).find('option:selected').val();
        if(catID) {
            $.ajax({
                url: '{{ action("BankHistoryController@getProjectNew")}}',
                type: "GET",
                data:{id:catID},
                dataType: "json",
                success:function(data) {

                    
                    $('select[name="store_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="store_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                        console.log(data);
                    });


                }
            });
        }else{
            $('select[name="store_id"]').empty();
        }
    });
    
    // another
    
    $('select[name="type_id"]').on('change', function() {
        $('#form_section').html('');
        var catID = $(this).find('option:selected').val();
        if(catID) {
            $.ajax({
                url: '{{ action("BankHistoryController@getCompanyNew")}}',
                type: "GET",
                data:{type_id:catID},
                dataType: "json",
                success:function(data) {
                    $('select[name="company_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="company_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                        console.log(data);
                    });


                }
            });
        }else{
            $('select[name="company_id"]').empty();
        }
    });
});

$(document).on('change','.clickToGet', function(){
    var current_value=Number($(this).val());
    var available=Number($(this).data('qty'));

    if (available < current_value) {
        swal('Warning','Quantity Is Overed' ,'error');
        $(this).val(available);
        return ;
    }
    
    
    let id = parseInt($(this).attr("id").substr(4));
        let qty_input = $('#qty-' + id);
        let unit_price_input = $('#utp-' + id);
        let qty = qty_input.val();
        let unit_price = unit_price_input.val();
        let store_id =$("select[name='store_id']").find('option:selected').val();

        setTimeout(function (){
        if (unit_price_input.is(":focus") || qty_input.is(":focus")) {
            return;
        }
            $.ajax({
                url:"{{ action('StockReturnController@cartUpdate')}}",
                type:'GET',
                datatype:'html',
                cache : false,
                async: false,
                data:{id,qty,unit_price,store_id},
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
        }, 500);
        

});


$(document).on("click", "#DeleteButton", function(e) {
        e.preventDefault(e);
         $(this).closest("tr").remove();
         calculation();
});


$(document).on('blur change','.changeToGet, .old_sale_unit_price',function(){
    
    var current_value=Number($(this).val());
    var available=Number($(this).data('qty'));

    if (available < current_value) {
        swal('Warning','Quantity Is Overed' ,'error');
        $(this).val(available);
        return ;
    }



    let id = parseInt($(this).attr("id").substr(4));
    let qty = $('#qty-' + id).val();
    let unit_price = $('#utp-' + id).val();
    $('#set-' + id).text((qty*unit_price).toFixed(2));
    
    
    calculation();
});

</script>
@endpush
@endsection
