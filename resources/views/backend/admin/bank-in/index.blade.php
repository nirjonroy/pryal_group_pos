@extends('layouts.backend.app')
@section('page_title') | Expense Index @endsection
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

    <h4 class="font-weight-bold py-3 mb-0">Bank Amount In</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <a href="{{ route('bank-in.create') }}" class="btn btn-primary pull-right">Create</a>
                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Responsible Person</th>
                                <th>Bank  Name</th>
                                
                                <th>Account No</th>
                                
                                
                                <th>Cash In</th>
                                <th>Description of Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank_in as $item)
                            <tr>
                                <td>
                                    {{ $loop->index+1 }}
                                </td>

                                <td>
                                    {{date('d.m.Y', strtotime($item->created_at)) }}
                                </td>
                                 <td>
                                    {{ $item->responsible_person }}
                                </td>
                                <td>
                                    {{ $item->bank_name }}
                                </td>
                               
                                <td>
                                    {{ $item->ac_no }}
                                </td>
                                <td>
                                    {{ $item->amount }}

                                </td>
                                <td>
                                    {{ $item->note }}

                                </td>
                                
                                
                                
                                <td style="width:10%;">
                                    <div class="btn-group" id="hover-dropdown-demo">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-trigger="hover">Action</button>
                                        <div class="dropdown-menu">
                                    
                                            @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                                <a class="dropdown-item" href="{{ route('bank-in.edit',$item->id) }}">Edit</a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin'))
                                             <a class="dropdown-item" href="{{ route('bank-in.edit',$item->id) }}">Edit</a>
                                            @endif

                                         @if(auth()->user()->hasRole('admin'))   
                                        <form action="{{ route('bank-in.destroy' , $item->id)}}" method="POST">
                                            <input name="_method" type="hidden" value="DELETE">
                                            {{ csrf_field() }}

                                            <input type="submit" value="delete" class="btn btn-sm btn-danger" onclick="return confirm(' you want to delete?');">
                                        </form>
                                         @endif
                                        </div>
                                    </div>
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
