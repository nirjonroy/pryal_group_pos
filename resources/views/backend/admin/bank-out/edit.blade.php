@extends('layouts.backend.app')
@section('page_title') | Bank History Update @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Bank Amount Out Update</h4>

    <form action="{{ route('bank-out.update',$data->id) }}" method="POST">
        @csrf
        {{ method_field('PATCH') }}
        <div class="form-group">
            <label class="form-label">Responsible Person<span class="red">*</span></label>
            <input value="{{ $data->bank_name }}" name="bank_name" type="text" class="form-control" placeholder="Responsible Person">
            @if ($errors->has('bank_name'))
            <span class="red" role="alert">
                <strong>{{ $errors->first('bank_name') }}</strong>
            </span>
            @endif
            <div class="clearfix"></div>
        </div>


        <div class="form-group">
            <label class="form-label">Amount<span class="red">*</span></label>
            <input value="{{ $data->amount }}" name="amount" type="number" step="any" class="form-control" placeholder="Enter Amount">
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
            <input type="date" step="any" name="date" class="form-control" value="{{date('Y-m-d', strtotime($data->created_at))}}">
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
            <input type="text" step="any" name="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" readonly>
            <div class="clearfix"></div>
        </div>

        @endif

        <div class="form-group">
            <label class="form-label">Note<span class="red">*</span></label>
            <textarea class="form-control" name="note" placeholder="Note Here..">{{ $data->note }}</textarea>
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
