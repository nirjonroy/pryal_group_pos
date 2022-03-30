@extends('layouts.backend.app')
@section('page_title') | Supplier Type Update @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Type Update</h4>

    <form action="{{ route('supplier-type.update',$data->id) }}" method="POST">
        @csrf
        {{ method_field('PATCH') }}
       <div class="form-group">
            <label class="form-label">Type Name<span style="color:red">*</span></label>
            <input name="name" type="text" class="form-control" value="{{$data->name}}" required>
    
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
