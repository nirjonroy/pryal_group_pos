@extends('layouts.backend.app')
@section('page_title') | Expense Type Create @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Expense Type Create</h4>

    <form action="{{ route('expense-type.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Type Name<span style="color:red">*</span></label>
            <input value="{{ old('name') }}" name="name" type="text" class="form-control" placeholder="Enter Name" required>
    
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
