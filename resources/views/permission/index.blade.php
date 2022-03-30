@extends('layouts.backend.app')
@section('page_title') |Permission @endsection

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Permission List</h4>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <a href="{{ route('permission.create') }}" class="btn btn-primary pull-right">Create</a><br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Permission Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                            <tr>
                                <td>
                                    {{ $loop->index+1 }}
                                </td>

                                <td>
                                    {{ $item->name }}
                                </td>
                                
                                <td>
                
                                    <a class="btn btn-success btn-sm" href="{{ route('permission.edit',$item->id) }}"><i class="fa fa-edit"></i></a>
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
