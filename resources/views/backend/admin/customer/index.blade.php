@extends('layouts.backend.app')
@section('page_title')
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

    <h4 class="font-weight-bold py-3 mb-0">Customer List</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.customer.create') }}">Customer Create</a></li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form class="no-print">
                    <div class="row">
                       
                        
                        
                        <div class="form-group col-md-1">
                                <br><br><a class="btn btn-sm btn-primary" onclick="imprimir()">Print</a>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                        
                    </div>
                </form>
                    <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>
                <div class="card-datatable table-responsive" >
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Customer Name</th>
                                <th>Contract Phone</th>
                                <th>Address</th>
                                <th>Description</th>
                                <th>Total Sell</th>
                                <th>Total Payment</th>
                                <th>Total Due</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customer as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    {{$item->name}}
                                </td>
                                <td>
                                    {{ $item->contract_phone }}
                                </td>
                                <td>
                                    {{$item->address}}
                                </td>
                                
                                <td>{{$item->note}}</td>
                                <td>{{$item->sell->sum('total_price')}}</td>
                                <td>{{$item->sell_payment->sum('total_price')}}</td>
                                <td>{{$item->sell->sum('total_price') - $item->sell_payment->sum('total_price') }}</td>
                                <td style="width:10%;" class="no-print">
                                    
                                    @if(auth()->user()->can('customer.delete'))
                                    <a onclick="return confirm('Are you sure?')" href="{{ route('admin.customer.delete',$item->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                    @endif
                                    
                                    
                                    @if(auth()->user()->can('customer.view'))
                                    <a href="{{ route('admin.customer.view',$item->id) }}" class="btn btn-sm btn-warning">view</a>
                                    @endif
                                    
                                    
                                     @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                        <a class="dropdown-item" href="{{ route('admin.customer.edit',$item->id) }}">Edit</a>
                                    @endif
                                    @if(auth()->user()->hasRole('admin'))
                                     <a class="btn btn-sm btn-success" href="{{ route('admin.customer.edit',$item->id) }}">Edit</a>
                                    @endif
                                    <!-- <form action="{{ route('bank-out.destroy' , $item->id)}}" method="POST">-->
                                    <!--            <input name="_method" type="hidden" value="DELETE">-->
                                    <!--            {{ csrf_field() }}-->

                                    <!--            <input type="submit" value="delete" class="btn btn-sm btn-danger">-->
                                    <!--</form>-->
                                    
                                    @if(auth()->user()->can('customer.payment'))
                                        @if($item->sell_payment->sum('total_price') < $item->sell->sum('total_price'))
                                    <a class="btn btn-sm btn-warning btn_modal" data-href="{{ route('getCustomerPaymentModal',[$item->id]) }}">Add Payment</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
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
<div class="modal fade container" id="container" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
@push('js')
<script src="{{ asset('backend/links')}}/assets/libs/datatables/datatables.js"></script>
<script src="{{ asset('backend/links')}}/assets/js/pages/tables_datatables.js"></script>


<script>
    
     $(document).on('click', 'a.btn_modal', function(){
        
        $.ajax({
            url: $(this).data('href'),
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

     var amount=0;
     $(document).on('keyup','#payment_amount',function(){
         amount=$(this).val();
         var due=$('#due').val();
         update_amount=(due-amount);
         $('#due_amount').text(update_amount);


     });
     
</script>
@endpush
@endsection
