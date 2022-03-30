@extends('layouts.backend.app')
@section('page_title') | Expense Create @endsection
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

    <h4 class="font-weight-bold py-3 mb-0">Create Expense</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.expense.index') }}">Expense List</a></li>
        </ol>
    </div>

    <form action="{{ route('admin.expense.store') }}" method="POST" autocomplete="off" >
        @csrf
    <div class="row">
        
        
        
        
        

        
    </div>
    <div class="row">
        
        <div class="col-md-4">
           @if(auth()->user()->hasRole('admin'))
            <div class="card mb-4">
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" class="form-control" required id="date">
                </div>
            </div>
            @else
            <?php
            $timezone = "Asia/Colombo";
            date_default_timezone_set($timezone);
            $today = date("Y-m-d");
           ?>
           <div class="card mb-4">
                <div class="card-body">
                   <label>Date</label>
                    <input type="date" name="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" required id="date" readonly>
                </div>
            </div>
            @endif

        </div>
        
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label for="Project">Category:</label>
                    <select name="category_id"  class="form-control select2" required>
                        <option value="" hidden>Select Your Category</option>
                        @foreach ($cats as $item)
                        <option  {{ old('category_id') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                    <span class="red" role="alert">
                        <strong>{{ $errors->first('category_id') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <label>Description</label>
                    <textarea name="note" class="form-control" placeholder="Enter Your Description.."></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h6 class="card-header">
                    <label for="">Expense Details</label>
                </h6>
                <div class="card-body">
                    <table class="datatables-demo table table-striped table-bordered" id="item_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th  style="width:30%;">Expense Title</th>
                                <th  style="width:40%;">Short Description</th>
                                <th style="width: 10%">Total</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><label class="form-check-label">#1</label></td>
                                <td  style="width:30%;">
                                    <select class="form-control" name="type_id[]" required>
                                        <option>Select A Type</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td  style="width:40%;">
                                    <input name="description[]" autocomplete="off"  style="width:100%;" type="text" placeholder="" class="col-xl-8 col-lg-8 col-12 form-control">
                                </td style="width: 10%">
                                <td>
                                    <input name="total_price[]" value="0" autocomplete="off"  type="number" step="any" value="0" placeholder="" style="width:100%;" class="col-xl-8 col-lg-8 col-12 form-control finalTotal">
                                </td>
                    
                                <td>
                                    <button type="button" name="add" class="btn btn-success btn-sm add"><i class='fas fa-plus text-orange-green'></i></button>
                                    <button type="button" name="add" class="btn btn-danger btn-sm remove"><i style="color:yellow;" class="fas fa-times text-orange-red"></i></button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align: right">Total</th>
                                <th colspan="3">
                                    <strong id="showTotal"></strong>
                                    <input type="hidden" name="totalExpensePrice" id="showTotalVal">
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <input id="submit" type="submit" style="display: none;" class="btn btn-info pull-right" value="Expense Create">
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
<div id="addToCartProductDefaultLoading" data-url="{{route('admin.addToCartProductDefaultLoading')}}"></div>

@push('js')
<script>
    $(function(){
        $('.datepicker-base, .date').datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked",
            language: "de"
        });
    });
</script>

<input type="hidden" id="getProjectByCompanyId" value="{{ route('admin.getProjectByCompanyId') }}">
<script>
    $(document).ready(function(){
        $('#company_id').change(function(){
            var company_id = $('#company_id').val();
            var url = $('#getProjectByCompanyId').val();
            $.ajax({
                url:url,
                type:'GET',
                datatype:'html',
                cache : false,
                async: false,
                data:{company_id},
                success:function(response)
                {
                    if(response)
                    {
                        $('#project_id').html(response);
                    }else{
                        return;
                    }
                },
            });
        });
        // default=======================
            var company_id =  $('#company_id option:selected').val();
            var url = $('#getProjectByCompanyId').val();
            $.ajax({
                url:url,
                type:'GET',
                datatype:'html',
                cache : false,
                async: false,
                data:{company_id},
                success:function(response)
                {
                    if(response)
                    {
                        $('#project_id').html(response);
                    }else{
                        return;
                    }
                },
            });
        // default=======================
    });
</script>

<script>
    $(document).ready(function(){
    
        //================ Add more and remove end here=====================================
        $(document).on('click', '.add', function(){
            let count = 1;
            count = count + $('#item_table >tbody >tr').length;
            if(count <= 100)
            {
                let html = '';
                html += '<tr>';
                html += '<td>'+"#"+count  +'</td>';
                html += '<td><select class="form-control" name="type_id[]" required><option>Select A Type</option>@foreach($types as $type)<option value="{{$type->id}}">{{$type->name}}</option>@endforeach</select></td>';
                html += '<td><input autocomplete="off"  type="text" name="description[]" style="width:80%;" class="form-control" /></td>';
                html += '<td><input autocomplete="off" value="0"  type="number" step="any" name="total_price[]" value="0" style="width:80%;" class="form-control finalTotal" /></td>';

                html += '<td><button type="button" name="add" class="btn btn-success btn-sm add" style="margin-right:3%;"><i class="fas fa-plus text-orange-green"></i></button><button type="button"  name="remove" class="btn btn-danger btn-sm remove"><i style="color:yellow;" class="fas fa-times text-orange-red"></i></button></td></tr>';
                $('#item_table').append(html);
            }
        });
        
        $(document).on('click', '.remove', function(){
            $(this).closest('tr').remove();

            //========================
            let total = 0;
            $('.finalTotal').each(function() 
            {
                total += parseFloat($(this).val());
            })
            if(isNaN(total)) {
                total = 0;
            }
            $('#showTotal').text(total);
            $('#showTotalVal').val(total);

            if(total > 0)
            {
                $('#submit').show();
            }
            else{
                $('#submit').hide();
            }
            //========================
        });
    //================ Add more and remove end here=====================================

    
    $(document).on('blur , change','.finalTotal',function(){
        let total = 0;
        $('.finalTotal').each(function() 
        {
            total += parseFloat($(this).val());
        })
        if(isNaN(total)) {
            total = 0;
        }
        $('#showTotal').text(total);
        $('#showTotalVal').val(total);

        if(total > 0)
        {
            $('#submit').show();
        }
        else{
            $('#submit').hide();
        }
    });
        let total = 0;
        $('.finalTotal').each(function() 
        {
            total += parseFloat($(this).val());
        })
        if(isNaN(total)) {
            total = 0;
        }
        $('#showTotal').text(total);
        $('#showTotalVal').val(total);
    });
    </script>


<script type="text/javascript">
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
                    if (data.length == 0) {
                      alert("No Project Found");
                    }
                    else {
                    $.each(data, function(key, value) {
                        $('select[name="project_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                    }


                }
            });
        }else{
            $('select[name="company_id"]').empty();
        }
    });
});




// company type  wise company
$(document).ready(function() {
    $('select[name="company_type_id"]').on('change', function() {
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
                    
                    if (data.length == 0) {
                      alert("No Company Found");
                    }
                    else {
                    var option = '<option value="" hidden>Select A Company</optiopn>';
                    $.each(data, function(key, value) {
                        option +='<option value="'+ value +'">'+ key +'</option>';
                    });
                    $('select[name="company_id"]').append(option);
                    }
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
