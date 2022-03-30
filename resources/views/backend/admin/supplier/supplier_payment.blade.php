@extends('layouts.backend.app')
@section('page_title') Received Payment @endsection
@push('css')
<style>

</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
    <h4 class="font-weight-bold py-3 mb-0">Supplier Wise Payment</h4>
   
    <div class="row">
        <div class="col-md-5">
            <label>Supplier Type:</label>
            <select class="form-control select2" name="type" id="type">
                <option value="">Select Your  Type</option>
                @foreach($types as $type)
                <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select><br>
            <div id="form_section">
                
            </div>
        </div>

        <div class="col-md-5">
            <label>Supplier:</label>
            <select class="form-control select2" name="supplier_id" id="supplier_id">
                <option value="">Select Your Supplier</option>
            </select><br>
        </div>

        <div class="col-md-2">
            <br><button class="btn-sm btn-primary" id="submit">SUMBIT</button>
        </div>
    </div>
</div>

<div class="modal fade container" id="container" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>

@push('js')

<script type="text/javascript">
     $(document).on( 'click', '#submit', function(){
        var supplier_id=$('#supplier_id').find('option:selected').val();
            if (supplier_id!='') {

                $.ajax({
                url: '{{url("admin/supplier/payment-modal")}}',
                data:{supplier_id:supplier_id},
                dataType: "html",
                success: function(result){
                    $('.container').html(result).modal('show');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    alert('Something Went Wrong'); 
                }  
            });
            }else{
                alert('please select a supplier');
            }
            
        
    });

     var amount=0;
     $(document).on('keyup','#payment_amount',function(){
         amount=$(this).val();
         var due=$('#due').val();
         update_amount=(due-amount);
         $('#due_amount').text(update_amount);


     });

//  
$(document).ready(function() {
    $('select[name="type"]').on('change', function() {
        var catID = $(this).find('option:selected').val();
        if(catID) {
            $.ajax({
                url: '{{ action("BankHistoryController@getsupplierNew")}}',
                type: "GET",
                data:{id:catID},
                dataType: "json",
                success:function(data) {

                    
                    $('select[name="supplier_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="supplier_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                        console.log(data);
                    });


                }
            });
        }else{
            $('select[name="type"]').empty();
        }
    });
}); 

</script>
@endpush
@endsection
