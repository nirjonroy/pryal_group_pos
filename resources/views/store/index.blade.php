@extends('layouts.backend.app')
@section('page_title') |Permission @endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
    <h4 class="font-weight-bold py-3 mb-0">Store List</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @if(auth()->user()->can('stores.create'))
                <a href="{{ route('stores.create') }}" class="btn btn-primary pull-right">Store Create</a><br>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Store Name</th>
                                <th> Total Product In Stock </th>
                                <th> Total Amount </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($items as $item)
                            <tr>
                                <td>
                                    {{ $loop->index+1 }}
                                </td>

                                <td>
                                    {{ $item->name }}
                                </td>
                                <td> {{ $item->qty ??0 }}</td>
                                <td> {{ number_format($item->total_amount,2) }}</td>
                                
                                <td>
                                    @if(auth()->user()->can('stores.edit'))
                                    <a class="btn btn-success btn-sm" href="{{ route('stores.edit',[$item->id])}}"><i class="fa fa-edit"></i></a>
                                    
                                   @endif
                                   <a onclick="return confirm('Are you sure?')" class="btn btn-warning btn-sm" href="{{ route('stores.delete',[$item->id])}}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-secondary" style="color: #fff">
                                <th colspan="2" class="text-right">Total =</th>
                                <th><strong>{{ number_format($items->sum('qty'),2) }}</strong></th>
                                <th><strong>{{ number_format($items->sum('total_amount'),2) }}</strong></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
