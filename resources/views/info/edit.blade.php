@extends('layouts.backend.app')
@section('page_title') | Company Info @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Company Info Update</h4>

    <form action="{{ action('InfoController@update',$row->id) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="form-group">
            <label class="form-label">Name </label>
            <input value="{{ $row->name }}" name="name" type="text" class="form-control">
            <div class="clearfix"></div>
        </div>

        <div class="form-group">
            <label class="form-label">Addess </label>
            <input value="{{ $row->address }}" type="text" class="form-control" name="address">
            <div class="clearfix"></div>
        </div>

        

        <div class="form-group">
            <label class="form-label">Phone</label>
            <input value="{{ $row->note }}" type="text" class="form-control" name="note">
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
