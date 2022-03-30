@extends('layouts.backend.app')
@section('page_title') | User Create @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card">
    
        <div class="card-header">
            <h4 class="font-weight-bold py-3 mb-0">User Create</h4>

            @if(count($errors) > 0 )
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul class="p-0 m-0" style="list-style: none;">
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Name:</label>
                    <input value="{{old('name')}}" type="text" class="form-control" name="name" placeholder="Enter Name..">
                </div>

                 <div class="form-group">
                    <label>Phone Number:</label>
                    <input value="{{old('phone')}}" type="text" class="form-control" name="phone" placeholder="Enter Number..">
                </div>

                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input value="{{old('email')}}" type="email" class="form-control" name="email" placeholder="Enter Email..">
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter Password..">
                </div>

                <div class="form-group">
                    <label for="pwd">Confirm Password:</label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Enter Conform Password..">
                </div>

                <div class="form-group">
                    <label for="pwd">Role:</label>
                    <select class="form-control" name="roles[]">
                    <option value="" hidden>Select A Role</option>
                    @foreach($roles as $role)
                    <option value="{{$role->name}}">{{$role->name}}</option>
                    @endforeach
                    </select>
                </div>

              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

@push('js')

@endpush
@endsection
