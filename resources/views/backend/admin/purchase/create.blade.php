@extends('layouts.backend.app')
@section('page_title') | Purchase Create @endsection
@push('css')
<link rel="stylesheet" href="{{ asset('backend/links') }}/assets/libs/bootstrap-sweetalert/bootstrap-sweetalert.css">
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

    <h4 class="font-weight-bold py-3 mb-0">Create Purchase</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.purchase.index') }}">Supplier List</a></li>
        </ol>
    </div>

    <form action="{{ route('admin.purchase.store') }}" method="POST">
        @csrf
    <div class="row">
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="supplier">Supplier Name :</label>
                    <select name="supplier_id"  class="form-control select2" required>
                        <option value="">Select One</option>
                        @foreach ($suppliers as $item)
                        <option {{ old('supplier_id') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
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
                    <label for="supplier">Company Type:</label>
                    <select name="type_id"  class="form-control select2" required>
                        <option value="" hidden>Select A Type</option>
                        @foreach ($comTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="Project">Company:</label>
                    <select name="company_id"  class="form-control select2" required>
                        <option value="" hidden>Select Your Company</option>
                    </select>
                    @if ($errors->has('company_id'))
                    <span class="red" role="alert">
                        <strong>{{ $errors->first('company_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="Project">Project Name :</label>
                    <select name="project_id"  class="form-control select2" required >
                        <option value="" >Company Select First</option>
                    </select>
                    @if ($errors->has('project_id'))
                    <span class="red" role="alert">
                        <strong>{{ $errors->first('project_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>





        <?php
            $timezone = "Asia/Colombo";
            date_default_timezone_set($timezone);
            $today = date("Y-m-d");
        ?>

        <div class="col-md-4">
           @if(auth()->user()->hasRole('admin'))
            <div class="card mb-4">
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" class="form-control" required id="date" value="{{ $today }}">
                </div>
            </div>
            @else
            
           <div class="card mb-4">
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" value="{{$today}}" class="form-control" required id="date" readonly>
                </div>
            </div>
            @endif

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

                        <div class="form-group col-md-6">
                            <label for="">Note / Description :</label>
                            <textarea class="form-control" name="description" placeholder="Enter Note Here.."></textarea>
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
                        <tbody id="showResult">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th colspan="2">
                                    <strong id="totalQty"></strong>
                                    <input type="hidden" name="totalProductQuantity" id="totalProductQuantity">
                                </th>
                                <th>
                                    <strong id="totalAmount"></strong>
                                    <input type="hidden" id="totalProductPrice" name="totalProductPrice" value="">
                                </th>
                                <th>
                                    <a href="{{ route('admin.addToCartProductRemoveAll') }}" class="btn btn-primary">Remove Cart</a>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row" style="margin-bottom: 5%;margin-top:10%;">
                        <div class="col-md-6">
                            <label for="">Payment Method</label>
                            <select name="payment_method_id" id="" class="form-control">
                                @foreach ($payment_methods as $item)
                                <option  {{ old('payment_method_id') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Payment Amount</label>
                            <input type="number" step="any" name="payment_amount" id="paymentAmount" class="form-control">
                        </div>
                    </div>
                    <input id="submit" type="submit"  class="btn btn-info pull-right" value="Purchase Now">
                </div>
            </div>
        </div>
    </div>
</form>

<!--########################################################################-->
<!--########################################################################-->
<!---main content page end div-->
</div>
<!---main content page end div-->
<!--########################################################################-->
<!--########################################################################-->
<div id="getUrl" data-url="{{route('admin.addToCartProduct')}}"></div>
<div id="addToCartProductDefaultLoading" data-url="{{route('admin.addToCartProductDefaultLoading')}}"></div>
<div id="addToCartProductUpdateQtyPrice" data-url="{{route('admin.addToCartProductUpdateQtyPrice')}}"></div>
@push('js')

<script src="{{ asset('backend/links') }}/assets/libs/bootstrap-sweetalert/bootstrap-sweetalert.js"></script>

<!-------------Calculation------------>
<script>

    function calculation(){
        let total_qty = 0;
        $('.clickToGet').each(function()
        {
            total_qty += parseFloat($(this).val());
        })
        if(isNaN(total_qty)) {
            total_qty = 0;
        }
        $('#totalQty').text(total_qty);
        $('#totalProductQuantity').val(total_qty);

        let total = 0;
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

        //default loading
        var totalProductAmount =  $('#totalProductPrice').val();
        if(totalProductAmount > 0)
        {
            $('#submit').removeAttr('disabled','disabled');
        }else{
            $('#submit').attr('disabled','disabled');
        }
        //default loading
        let url = $('#addToCartProductDefaultLoading').data('url');
        $.ajax({
            url:url,
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
        $(document).on('blur','.clickToGet, .sale_unit_price', function(){
        //let unit_price = $('.clickToGet').data('unit_price');
        let id = parseInt($(this).attr("id").substr(4));
        let qty_input = $('#qty-' + id);
        let unit_price_input = $('#utp-' + id);
        let qty = qty_input.val();
        let unit_price = unit_price_input.val();
        //let sub_total = (unit_price_custom * qty).toFixed(2);
        //$("#set-" + id).text(sub_total);
        setTimeout(function (){
        if (unit_price_input.is(":focus") || qty_input.is(":focus")) {
            return;
        }
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
        var proID = $(this).find('option:selected').val();
        if(proID) {
            $.ajax({
                url: '{{ action("BankHistoryController@getProjectNew")}}',
                type: "GET",
                data:{id:proID},
                dataType: "json",
                success:function(data) {

                    
                    $('select[name="project_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="project_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                        console.log(data);
                    });


                }
            });
        }else{
            $('select[name="project_id"]').empty();
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
                    $('select[name="company_id"]').append('<option value="0">Select One</option>');
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
</script>
@endpush
@endsection
