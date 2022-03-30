@extends('layouts.backend.app')
@section('page_title') |User @endsection

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">User List</h4>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <a href="{{ route('user.create') }}" class="btn btn-primary pull-right">Create</a><br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>User Phone</th>
                                <th>User Email</th>
                                <th>User Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                            <tr>
                                <td>
                                    {{ $loop->index+1 }}
                                </td>

                                <td>{{ $item->name }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    @foreach($item->roles as $role)
                                    {{$role->name}}
                                    @endforeach
                                </td>
                                
                                <td>
                
                                    <a class="btn btn-success btn-sm" href="{{ route('user.edit',$item->id) }}">Edit</a><br>

                                    <form action="{{ route('user.destroy' , $item->id)}}" method="POST">
                                        <input name="_method" type="hidden" value="DELETE">
                                        {{ csrf_field() }}

                                        <input type="submit" onclick="return confirm('Are you sure you want to delete this item')" value="delete" class="btn btn-sm btn-danger">
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!--########################################################################-->
<!--########################################################################-->
<!---main content page end div-->
</div>
@endsection
