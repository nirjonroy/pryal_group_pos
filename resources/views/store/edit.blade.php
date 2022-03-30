@extends('layouts.backend.app')
@section('page_title') | Store  Update @endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')

    <h4 class="font-weight-bold py-3 mb-0">Store Update</h4>

    <form action="{{ route('stores.update',$store->id) }}" method="POST">
        @csrf
        {{ method_field('PATCH') }}
       <div class="form-group">
            <label class="form-label">Type Name<span style="color:red">*</span></label>
            <input name="name" type="text" class="form-control" value="{{$store->name}}" required>
    
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>

    </form>
</div>
@endsection
