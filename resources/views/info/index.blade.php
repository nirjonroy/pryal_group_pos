@extends('layouts.backend.app')
@section('page_title') |Compnay Info  @endsection
@push('css')
<style>

</style>
<link rel="stylesheet" href="{{ asset('backend/links') }}/assets/libs/datatables/datatables.css">
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Company Info</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                
                                <th>NAme</th>
                                <th>Address</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                            <tr>
                                
                                
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->address }}</td>
                                <td>{{  $item->phone }}</td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ action('InfoController@edit',$item->id)}}">Edit</a>
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
<!---main content page end div-->
<!--########################################################################-->
<!--########################################################################-->

@push('js')
<script src="{{ asset('backend/links')}}/assets/libs/datatables/datatables.js"></script>
<script src="{{ asset('backend/links')}}/assets/js/pages/tables_datatables.js"></script>



@endpush
@endsection
