@extends('layouts.backend.app')
@section('page_title') Received Payment @endsection
@push('css')
<style>

</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
    <h4 class="font-weight-bold py-3 mb-0">Received Payment</h4>
   
    <div class="row">
        <div class="col-md-3">
            <form>
                <label>Company Type:</label>
                <select class="form-control select2" name="type_id" onchange="this.form.submit()">
                    <option value="" >Select Company Type</option>
                    @foreach($comTypes as $type)
                    <option value="{{$type->id}}" {{request()->type_id==$type->id ?'selected':''}} >{{$type->name}}</option>
                    @endforeach
                </select><br>
            </form>
        </div>
        
        
        <div class="col-md-4">
            <label>Company:</label>
            <select class="form-control select2" name="company_id" id="company_id">
                <option value="" >Select Your Company</option>
                @foreach($coms as $com)
                <option value="{{$com->id}}">{{$com->name}}</option>
                @endforeach
            </select><br>
        </div>


        <div class="col-md-4">
            <label>Project:</label>
            <select class="form-control select2" name="project_id" id="project_id">
               
            </select><br>
        </div>


        <div class="col-md-1">
            <br><button class="btn-sm btn-primary" id="submit">SUMBIT</button>
        </div>
    </div>
</div>

<div class="container">
        <div id="form_section" class="p-4">
                    
        </div>
</div>

@push('js')

<script type="text/javascript">
     $(document).on( 'click', '#submit', function(){
        var company_id=$('select#company_id').find('option:selected').val();
        var project_id=$('select#project_id').find('option:selected').val();

        if (company_id !='' && project_id !='') {
               $.ajax({
                url: '{{url("admin/project/get-project")}}',
                data:{project_id:project_id,company_id:company_id},
                dataType: "html",
                success: function(result){
                    $('#form_section').html(result);
                },
                error: function() { 
                    alert("something went Wrog"); 
                }  
            }); 
        }else{
            alert('Please Select All Dropdown');
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
    $('select[name="company_id"]').on('change', function() {
        $('#form_section').html('');
        var catID = $(this).find('option:selected').val();
        if(catID) {
            $.ajax({
                url: '{{ action("BankHistoryController@getProjectNew")}}',
                type: "GET",
                data:{id:catID},
                dataType: "json",
                success:function(data) {

                    
                    $('select[name="project_id"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="project_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });


                }
            });
        }else{
            $('select[name="project_id"]').empty();
        }
    });
}); 
</script>
@endpush
@endsection
