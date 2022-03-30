@extends('layouts.backend.app')
@section('page_title') | Supplier Type @endsection

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Supplier Type List</h4>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <a href="{{ route('supplier-type.create') }}" class="btn btn-primary pull-right">Create</a><br>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sups as $item)
                            <tr>
                                <td>
                                    {{ $loop->index+1 }}
                                </td>

                                <td>
                                    {{ $item->name }}
                                </td>
                                
                                <td>
                
                                    <a class="btn btn-success btn-sm" href="{{ route('supplier-type.edit',$item->id) }}">Edit</a><br>

                                    <form action="{{ route('supplier-type.destroy' , $item->id)}}" method="POST">
                                        <input name="_method" type="hidden" value="DELETE">
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
