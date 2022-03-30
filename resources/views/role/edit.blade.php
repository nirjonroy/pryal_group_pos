@extends('layouts.backend.app')
@section('page_title') | Role Update @endsection
@push('css')

@endpush

@section('content')
<!--########################################################################-->
<div class="container">
<div class="row">
<div class="col-md-12">
<!--########################################################################-->
    <h4 class="font-weight-bold py-3 mb-0">Role Update</h4>
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

    <form action="{{ route('role.update',$role->id) }}" method="POST">
        @csrf
        {{ method_field('PATCH') }}
        <div class="form-group col-md-12">
            <label class="form-label">Role Name<span style="color:red">*</span></label>
            <input value="{{ $role->name }}" name="name" type="text" class="form-control" placeholder="Enter Name" required>
            <div class="clearfix"></div>
        </div><hr>

        <div class="col-md-12">
            <h5 class="font-weight-bold py-3 mb-0">Assign Permission</h5>

            <div class="form-group col-md-12">
                <div class="checkbox">
                  <label><input type="checkbox" value="1" id="all_select">  All</label>
                </div>
            </div>

            <table class="table table-striped table-bordered">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                </tr>

                @foreach($permissions as $item)
                <tr>
                    <td><input type="checkbox" name="permission[]" value="{{$item->name}}" {{$role->hasPermissionTo($item->name) ?'checked':''}}></td>
                    <td>{{$item->name}}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="form-group col-md-12">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>

    </form>
</div>
</div>
</div>
@push('js')
<script type="text/javascript">
    
    $('#all_select').click(function(){

        if($(this).is(':checked')){
            $('input[type=checkbox]').prop('checked',true);
        }else{
            $('input[type=checkbox]').prop('checked',false);
        }
    })
</script>
@endpush
@endsection
