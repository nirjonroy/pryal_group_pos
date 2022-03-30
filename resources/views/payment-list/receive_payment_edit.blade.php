@extends('layouts.backend.app')
@section('page_title') | Bank History Create @endsection
@push('css')
@endpush
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
     <div class="row">
        <div class="col-md-10">
            <div class="card p-30">
                <h4 class="font-weight-bold py-3 mb-0">Receive Payment Update</h4>
            
                <form action="{{ action('PaymentController@receivedPaymentUpdate') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Company Name </label>
                        <select name="company_id" class="form-control select2" required>
                            @foreach($coms as $com)
                            <option value="{{$com->id}}" {{$com->id==$row->company_id ? 'selected' :''}} >{{$com->name}}</option>
                            @endforeach
                        </select>
                        <div class="clearfix"></div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="form-label">Project </label>
                        <select name="project_id" class="form-control select2" required>
                            @foreach($projects as $project)
                            <option value="{{$project->id}}" {{$project->id==$row->project_id ? 'selected' :''}} >{{$project->name}}</option>
                            @endforeach
                        </select>
                        <div class="clearfix"></div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label">Payment Method </label>
                        <select name="payment_method_id" class="form-control select2" required>
                            @foreach($methods as $method)
                            <option value="{{$method->id}}" {{$method->id==$row->payment_method_id ? 'selected' :''}} >{{$method->method}}</option>
                            @endforeach
                        </select>
                        <div class="clearfix"></div>
                    </div>
            
            
                    <div class="form-group">
                        <label class="form-label">Amount<span style="color:red">*</span></label>
                        <input value="{{$row->payment_amount}}" name="amount" type="number" step="any" class="form-control">
                        @if ($errors->has('amount'))
                        <span class="red" role="alert">
                            <strong>{{ $errors->first('amount') }}</strong>
                        </span>
                        @endif
                        <div class="clearfix"></div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="{{date('Y-m-d', strtotime($row->created_at))}}">
                        <div class="clearfix"></div>
                    </div>
            
            
                    <div class="form-group">
                        <label class="form-label">Note</label>
                        <textarea class="form-control" name="note" placeholder="Note Here..">{{ $row->note }}</textarea>
                        <div class="clearfix"></div>
                    </div>
            
                    <div class="form-group">
                        <input type="hidden" name="id" value="{{$row->id}}">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
            
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    $(document).ready(function() {
    $('select[name="company_id"]').on('change', function() {
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
