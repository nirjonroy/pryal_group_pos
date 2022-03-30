@extends('layouts.backend.app')
@section('page_title') | Bank History Create @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Bank Amount Out Create</h4>
    <h6 class="font-weight-bold py-3 mb-0">Bank Amount In Hand : <span style="color:red">{{$amount_has_total}}</span></h6>

    <form action="{{ route('bank-out.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Bank  Name<span style="color:red">*</span></label>
            <input value="{{ old('bank_name') }}" name="bank_name" type="text" class="form-control" placeholder="Responsible Person">
            @if ($errors->has('bank_name'))
            <span class="red" role="alert">
                <strong>{{ $errors->first('bank_name') }}</strong>
            </span>
            @endif
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label class="form-label">Responsible  Person<span style="color:red">*</span></label>
            <input value="{{ old('responsible_person') }}" name="responsible_person" type="text" class="form-control" placeholder="responsible person">
            @if ($errors->has('responsible_person'))
            <span class="red" role="alert">
                <strong>{{ $errors->first('responsible_person') }}</strong>
            </span>
            @endif
            <div class="clearfix"></div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Account No<span style="color:red">*</span></label>
            <input value="{{ old('ac_no') }}" name="ac_no" type="text" class="form-control" placeholder="Account No">
            @if ($errors->has('ac_no'))
            <span class="red" role="alert">
                <strong>{{ $errors->first('ac_no') }}</strong>
            </span>
            @endif
            <div class="clearfix"></div>
        </div>
    
        <div class="form-group">
            <label class="form-label">Amount<span style="color:red">*</span></label>
            <input value="{{ old('amount') }}" name="amount" type="number" step="any" class="form-control" placeholder="Enter Amount">
            @if ($errors->has('amount'))
            <span class="red" role="alert">
                <strong>{{ $errors->first('amount') }}</strong>
            </span>
            @endif
            <div class="clearfix"></div>
        </div>

        

        
         @if(auth()->user()->hasRole('admin'))
         <div class="form-group">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" >
            <div class="clearfix"></div>
        </div>
        @else
          <?php
          $timezone = "Asia/Colombo";
          date_default_timezone_set($timezone);
          $today = date("Y-m-d");
        ?>
        <div class="form-group">
            <label class="form-label">Date</label>
            <input  name="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
            <div class="clearfix"></div>
        </div>

         @endif


        <div class="form-group">
            <label class="form-label">Note</label>
            <textarea class="form-control" name="note" placeholder="Note Here..">{{ old('note') }}</textarea>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>

    </form>
</div>

@push('js')

@endpush
@endsection
