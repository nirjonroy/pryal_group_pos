@extends('layouts.backend.app')
@section('page_title','| Product Index') 
@push('css')
<style>

</style>
<link rel="stylesheet" href="{{ asset('backend/links') }}/assets/libs/datatables/datatables.css">
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0 no-print">Product</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.product.create') }}">Product Create</a></li>
        </ol>
    </div>
   <!--  <form class="no-print">
                        <div class="row">

                             <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>
                           
                        </div>

                        
                    </form> -->
<div class="form-group col-md-1">
    
<br><br><a class="btn btn-sm btn-primary no-print" onclick="imprimir()">Print</a>
</div>
<div class="form-group ">
      <br><br>
     <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
   
</div>


    <div class="row" >
        <div class="col-md-12">
            <div class="card">
                <h6 class="card-header">Product List</h6>
                <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>

                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th>Product unit</th>
                                <th> Product Purchase Price</th>
                                <th> Product Sell Price</th>
                                <th>Description</th>
                                
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    {{ $item->sku }}
                                </td>
                                <td>
                                    {{$item->name}}
                                </td>
                                <td>{{$item->unit->name}}</td>
                                <td>{{$item->unit_price}}</td>
                                <td>
                                    {{$item->sell_price}}
                                </td>
                                <td>
                                    {{$item->description}}
                                </td>
                               
                               
                                <td style="width: 165px;display: flex;" class="no-print">
                                    <a style="margin-right: 1px;" href="{{ route('admin.product.show',$item->id) }}" class="btn btn-sm btn-success">View</a>
                                     @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))

                                    <a style="margin-right: 1px;" href="{{ route('admin.product.edit',$item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    @endif
                                     @if(auth()->user()->hasRole('admin'))
                                      <a style="margin-right: 1px;" href="{{ route('admin.product.edit',$item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                     @endif
                                  

                                    @if(auth()->user()->hasRole('admin'))
                                    <form action="{{ route('admin.product.destroy', $item->id)}}" method="POST">
                                            <input name="_method" type="hidden" value="DELETE">
                                            {{ csrf_field() }}

                                            <input type="submit" value="delete" class="btn btn-sm btn-danger" onclick="return confirm(' you want to delete?');">
                                    </form>
                                    @endif
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
