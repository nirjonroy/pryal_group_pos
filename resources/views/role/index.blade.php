@extends('layouts.backend.app')
@section('page_title') |Role @endsection

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Role List</h4>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <a href="{{ route('role.create') }}" class="btn btn-success pull-right">Create</a><br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rol Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
                            <tr>
                                <td>
                                    {{ $loop->index+1 }}
                                </td>

                                <td>
                                    {{ $item->name }}
                                </td>
                                
                                <td style="display: flex;">
                
                                    <a style="margin-right: 3px;" class="btn btn-success btn-sm" href="{{ route('role.edit',$item->id) }}">Edit</a><br>

                                    <form {{$item->id == 1 ? 'hidden':''}} onclick="return confirm('Are you sure?');" action="{{ route('role.destroy' , $item->id)}}" method="POST">
                                        <input name="_method" type="hidden" value="DELETE" >
                                        {{ csrf_field() }}

                                        <input type="submit" value="delete" class="btn btn-sm btn-danger">
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
