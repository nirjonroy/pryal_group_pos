@extends('layouts.backend.app')
@section('page_title') | Stock Add List @endsection
@push('css')
<link rel="stylesheet" href="{{ asset('backend/links') }}/assets/libs/bootstrap-sweetalert/bootstrap-sweetalert.css">
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    @include('layouts.backend.partial.success_error_status_message')
    <h4 class="font-weight-bold py-3 mb-0">Stock List</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item no-print"><a href="{{ route('admin.stocks.create') }}"> Stock Add </a></li>
        </ol>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <div class="card p-2">
                 <form class="no-print">
                        <div class="row">

                             <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>
                            
                            

                            <div class="form-group col-md-3">
                                <label>Suppliers:</label>
                                <select class="form-control" name="supplier_id" onchange="this.form.submit()">
                                    <option value="" {{request()->supplier_id=='' ?'selected':''}}>All</option>
                                    @foreach($sups as $sup)
                                    <option value="{{$sup->id}}" {{request()->supplier_id==$sup->id ?'selected':''}}>{{$sup->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>From:</label>
                                <input type="date" name="date_start" class="form-control" placeholder="yyyy-mmm-dd" value="{{request()->date_start ?request()->date_start:''}}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>To:</label>
                                <input type="date" name="date_end" class="form-control" placeholder="yyyy-mmm-dd" value="{{request()->date_end ?request()->date_end:''}}">
                            </div>

                            <div class="form-group col-md-1">
                                <br><br><input type="submit" class="btn btn-primary btn-sm" value="submit">
                            </div>

                            <div class="form-group col-md-1">
                                <br><br><a class="btn btn-info btn-sm" href="{{ route('admin.purchase.index')}}">Refresh</a>
                            </div>

                            <div class="form-group col-md-1">
                                <br><br><a class="btn btn-sm btn-primary" onclick="imprimir()">Print</a>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                                 
                               
                            </div>
                        </div>
                            <a href="{{url('admin/supplier/supplier-payment')}}" class="btn btn-sm btn-warning no-print float-right">Payment</a>
                        
                    </form>
                    <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>

                <div class="card-datatable table-responsive" >
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Supplier</th>
                                <th>Store</th>
                                <th>Total Amount</th>
                                <th>Note</th>
                                <th>Total Payment</th>
                                <th>Total Due</th>
                                <th>Status</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $key=> $item)
                            <tr>
                                <td>
                                    {{ $key+ $purchases->firstItem() }}
                                </td>
                                <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                                <td>
                                    {{ $item->invoice_no }}
                                </td>
                                <td>
                                    {{ $item->suppliers->name }}
                                </td>
                                <td>
                                    {{ $item->store ?$item->store->name:'' }}
                                </td>
                                @php
                                    $purPric = $item->total_price;
                                    $car_r = $item->car_rent;
                                    $bost_cost = $item->bosta_cost;
                                    $labour_cost = $item->labour_cost;
                                    $other_cost = $item->other_cost;
                                    $total_sum = $purPric+$car_r+$bost_cost+$labour_cost+$other_cost;
                                @endphp
                                
                                <td>{{ $total_sum }}</td>

                                 <td>
                                    {{ $item->description }}
                                </td>
                                
                                 
                                 <td>{{$item->payments->sum('total_price')}}</td>
                                <td>{{$total_sum - $item->payments->sum('total_price')}}</td>
                                <td>
                                    {{$item->status}}
                                    
                                </td>
                    
                                <td style="width:10%;" class="no-print">
                                    <div class="btn-group" id="hover-dropdown-demo">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-trigger="hover">Action</button>
                                        <div class="dropdown-menu">
                                            @if(auth()->user()->can('purchase.view'))
                                            <a class="dropdown-item" class="btn btn-success btn-info" href="{{ route('admin.stocks.show',$item->id) }}">
                                                 <span class="btn btn-info btn-sm">View</span>
                                            </a>
                                            @endif
                                           
                                            
                                            @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                                <a class="dropdown-item" href="{{ route('admin.stocks.edit',$item->id) }}">
                                                    <span class="btn btn-info btn-sm">Edit</span>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin'))
                                             <a class="dropdown-item" href="{{ route('admin.stocks.edit',$item->id) }}">
                                                 <span class="btn btn-info btn-sm">Edit</span>
                                             </a>
                                            @endif
                                          
                                            @if(auth()->user()->hasRole('admin'))
                                                <form action="{{ route('admin.stocks.destroy' , $item->id)}}" method="POST">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    {{ csrf_field() }}

                                                    <input type="submit" style="margin-left:22px" value="delete" class="btn btn-sm btn-danger btn-sm" onclick="return confirm(' you want to delete?');">
                                                </form>
                                            @endif
                                            
                                            @if(auth()->user()->can('purchase.payment'))
                                                @if($item->total_price > $item->payments->sum('total_price'))
                                                <a class="dropdown-item payment_button" href="{{route('purchase-payments',[$item->id])}}" class="btn btn-success btn-info" id="addPayment">
                                                     <span class="btn btn-warning btn-sm">Add Payment</span>
                                                </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <th colspan="5" class="text-right" >Page Summery =</th>
                                <th>{{ number_format($purchases->sum('total_price'),2)}}</th>
                                

                            </tr>
                           
                      
                            <tr>
                                <th colspan="5" class="text-right" >Total Summery =</th>
                                <th>{{ number_format($total_summery,2)}}</th>
                            </tr>
                        </tbody>
                       

                    </table>
                </div>
                <p>{!! urldecode(str_replace("/?","?",$purchases->appends(Request::all())->render())) !!}</p>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade container" id="container" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>

@push('js')

<script src="{{ asset('backend/links') }}/assets/libs/bootstrap-sweetalert/bootstrap-sweetalert.js"></script>

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
<script>
    $(document).ready(function(){

        //set data in the modal===========
        $(document).on('click','.payment',function(e){
            e.preventDefault();
            var total_amount = $(this).data('total_amount');
            var paid_amount = $(this).data('paid_amount');
            var invoice_no = $(this).data('invoice_no');
            var id = $(this).data('id');
            var due_amount = total_amount - paid_amount;
            $('#invoice_no').text(invoice_no);
            $('#id').val(id);

            $('#total_amount').text(total_amount);
            $('#paid_amount').text(paid_amount);
            $('#due_amount').text(due_amount);

            $('#total_amount_value').val(total_amount);
            $('#paid_amount_value').val(paid_amount);
            $('#due_amount_value').val(due_amount);

            if(due_amount == 0)
            {
                $('#payment_amount').attr('disabled','disabled');
                $('#submit').attr('disabled','disabled');
            }else{
                $('#payment_amount').removeAttr('disabled','disabled');
            }
        });
        //set data in the modal===========


        $(document).on('keyup','#payment_amount',function(){
            var payment_value =  $(this).val();
            var due_amount_check = $('#due_amount_value').val();
            var remainingDue =  due_amount_check - payment_value;
            $('#due_amount').text(remainingDue);
            if(remainingDue == due_amount_check)
            {
                $('#submit').attr('disabled','disabled');
                // $('#payment_amount').css({
                //     'background-color':'red',
                //     'color':'yellow',
                //     'font-size':'17px;'
                // });
            }
            else if(remainingDue >= 0 ){
                $('#submit').removeAttr('disabled','disabled');
                $('#payment_amount').css({
                    'background-color':'green',
                    'color':'yellow',
                    'font-size':'17px;'
                });
            }
            else{
                $('#submit').attr('disabled','disabled');
                $('#payment_amount').css({
                    'background-color':'red',
                    'color':'yellow',
                    'font-size':'17px;'
                });
            }
        });


        $('.payment').click(function(e) { //button click class name is myDiv
           // e.stopPropagation();
            $('#submit').attr('disabled','disabled');
            $('#payment_amount').val('');
        })



        $('#addPayment').on("submit",function(e){
            e.preventDefault();
            var form = $(this);
            var url = form.attr("action");
            var type = form.attr("method");
            var data = form.serialize();
                $.ajax({
                    url: url,
                    data: data,
                    type: type,
                    datatype:"JSON",
                    beforeSend:function(){
                        //$('.loading').fadeIn();
                    },
                    success: function(data){
                        if(data == 'success')
                        {
                            swal("Great","Payment is Successfully","success");
                            form[0].reset(); 
                            location.reload();
                        }else{
                            swal("Wrong","Payment is Not Successfully","error");
                        }
                    },
                    complete:function(){
                    // $('.loading').fadeOut();
                    },
                });
            });


    });
</script>


<script> 
$(document).on('click', 'a.payment_button', function(e){
    e.preventDefault();
    
    var url=$(this).attr('href');

    
    $.ajax({
        url: url, 
        data:{},
        dataType: "html",
        success: function(result){
            $('.container').html(result).modal('show');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert('Something Went Wrong'); 
        }  
    });
   
    });


</script>

@endpush
@endsection
