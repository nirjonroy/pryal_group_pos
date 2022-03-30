@extends('layouts.backend.app')
@section('page_title') 
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

    <h4 class="font-weight-bold py-3 mb-0">Company</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.company.create') }}">Company Create</a></li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                 <form class="no-print">
                    <div class="row">

                        <div class="col-md-4">
                            <label>Type Of Company :</label>
                            <select class="form-control" name="type" onchange="this.form.submit()">
                                <option value="" hidden="hidden">Select A  Type</option>
                                @foreach($types as $type)
                                <option value="{{$type->id}}" {{request()->type==$type->id ?'selected':''}}>{{$type->name}}</option>
                                @endforeach
                                <option value="" {{request()->type=='' ?'selected':''}}>All</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label>Company :</label>
                            <select class="form-control" name="company_id" onchange="this.form.submit()">
                                @foreach($coms as $com)
                                <option value="{{$com->id}}" {{request()->company_id==$com->id ?'selected':''}}>{{$com->name}}</option>
                                @endforeach
                                <option value="" {{request()->company_id=='' ?'selected':''}}>All</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                               <br><br><a class="btn btn-xs btn-primary" onclick="imprimir()">Print</a>
                        </div>
                        <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                        
                    </div>
                </form>
                <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>

                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Comanay Name</th>
                                <th>Contract Person</th>
                                <th>Contract Phone</th>
                                <th>Address</th>
                                <th>Type</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    {{$item->name}}
                                </td>
                                <td>
                                    {{$item->contract_person}}
                                </td>
                                <td>
                                    {{ $item->contract_phone}}
                                </td>
                                <td>
                                    {{$item->address}}
                                </td>
                                
                                <td style="width:10%;" class="no-print">
                                    <a href="{{ route('admin.company.show',$item->id) }}" class="btn btn-sm btn-success">View</a>
                                   @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                    <a href="{{ route('admin.company.edit',$item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                     @endif
                                     @if(auth()->user()->hasRole('admin'))
                                      <a href="{{ route('admin.company.edit',$item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                      @endif

                                    @if(auth()->user()->hasRole('admin'))
                                   <form action="{{ route('admin.company.destroy', $item->id)}}" method="POST">
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
