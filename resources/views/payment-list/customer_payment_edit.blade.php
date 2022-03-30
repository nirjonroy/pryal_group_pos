@extends('layouts.backend.app')
@section('page_title') | Bank History Create @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Suuplier Payment Update</h4>

    <form action="{{ action('PaymentController@customerPaymentUpdate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">customer Name </label>
            <select name="customer_id" class="form-control select2">
                @foreach($cuss as $sup)
                <option value="{{$sup->id}}" {{$sup->id==$row->customer_id ? 'selected' :''}} >{{$sup->name}}</option>
                @endforeach
            </select>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label class="form-label">Payment Method </label>
            <select name="payment_method_id" class="form-control select2">
                @foreach($methods as $method)
                <option value="{{$method->id}}" {{$method->id==$row->method_id ? 'selected' :''}} >{{$method->method}}</option>
                @endforeach
            </select>
            <div class="clearfix"></div>
        </div>


        <div class="form-group">
            <label class="form-label">Amount<span style="color:red">*</span></label>
            <input value="{{$row->total_price}}" name="amount" type="number" step="any" class="form-control">
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
            <input type="date" name="date" class="form-control" value="{{date('Y-m-d', strtotime($row->created_at))}}">
            <div class="clearfix"></div>
        </div>
         @else
           <div class="form-group">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{date('Y-m-d', strtotime($row->created_at))}}" readonly>
            <div class="clearfix"></div>
        </div>
          @endif
        

        <div class="form-group">
            <label class="form-label">Note</label>
            <textarea class="form-control" name="note" placeholder="Note Here..">{{ $row->description }}</textarea>
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="hidden" name="id" value="{{$row->id}}">
            <input type="hidden" name="project_id" value="{{$row->project_id}}">
            <input type="hidden" name="company_id" value="{{$row->id}}">
        </div>


    </form>
</div>

@push('js')

@endpush
@endsection
