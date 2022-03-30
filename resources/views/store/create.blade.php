@extends('layouts.backend.app')
@section('page_title') | Store Create @endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')

    <h4 class="font-weight-bold py-3 mb-0">Store Create</h4>

    <form action="{{ route('stores.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Store Name<span style="color:red">*</span></label>
            <input value="{{ old('name') }}" name="name" type="text" class="form-control" placeholder="Enter Name" required>
    
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>

    </form>
</div>

@endsection
