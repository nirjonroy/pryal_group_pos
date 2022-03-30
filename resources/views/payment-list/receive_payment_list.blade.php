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

    <h4 class="font-weight-bold py-3 mb-0">Receive Payment List</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header no-print">
                 <form>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Company Type:</label>
                                <select class="form-control" name="type_id" onchange="this.form.submit()">
                                    <option value="" {{request()->type_id=='' ?'selected':''}}>All</option>
                                    @foreach($comTypes as $type)
                                    <option value="{{$type->id}}" {{request()->type_id==$type->id ?'selected':''}}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label>Company:</label>
                                <select class="form-control" name="company_id" onchange="this.form.submit()">
                                    <option value="" {{request()->company_id=='' ?'selected':''}}>All</option>
                                    @foreach($coms as $com)
                                    <option value="{{$com->id}}" {{request()->company_id==$com->id ?'selected':''}}>{{$com->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Project Status :</label>
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="" hidden="hidden">Select Stratus</option>
                                    <option value="0" {{request()->status=='0' ?'selected':''}}>Running</option>
                                    <option value="1" {{request()->status=='1' ?'selected':''}}>Complete</option>
                                    <option value="2" {{request()->status=='2' ?'selected':''}}>work by done</option>
                                    <option value="3" {{request()->status=='3' ?'selected':''}}>partner investment</option>
                                    <option value="" {{request()->status=='' ?'selected':''}}>All</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Projects:</label>
                                <select class="form-control" name="project_id" onchange="this.form.submit()">
                                    <option value="" {{request()->project_id=='' ?'selected':''}}>All</option>
                                    @foreach($projects as $project)
                                    <option value="{{$project->id}}" {{request()->project_id==$project->id ?'selected':''}}>{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>From:</label>
                                <input type="date" name="date_start" class="form-control" placeholder="yyyy-mmm-dd" value="{{request()->date_start ?request()->date_start:''}}">
                            </div>

                            <div class="form-group col-md-2">
                                <label>To:</label>
                                <input type="date" name="date_end" class="form-control" placeholder="yyyy-mmm-dd" value="{{request()->date_end ?request()->date_end:''}}">
                            </div>
                            <br><br>
                            <div class="form-group col-md-2">
                               <br><br> <input type="submit" class="btn btn-primary btn-sm" value="submit">
                            </div>

                            <div class="form-group col-md-2">
                              <br><br>  <a class="btn btn-info btn-sm" href="{{ action('PaymentController@receivedPaymentList')}}">Refresh</a>
                                <a class="btn btn-sm btn-primary no-print" onclick="imprimir()">Print</a>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                        </div>

                        
                    </form>
                </div>
                 <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>

                <div class=" table-responsive">
                    <table class=" table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Company</th>
                                <th>Project Name</th>
                                <th>Project Type</th>
                                <th>Receive Amount</th>
                                <th>Description</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $key=>$item)
                            <tr>
                                <td>
                                    {{$key+ $rows->firstItem()}}
                                </td>

                                <td>
                                    {{date('d.m.Y', strtotime($item->created_at))}}
                                </td>
                                <td>{{  $item->project->companies->name }}</td>
                                
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->project->type->name }}</td>
                                <td>{{  $item->payment_amount }}</td>
                                <td>
                                    {{ $item->note }}

                                </td>
                                <td class="no-print">
                                   
                                     @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                         <a class="btn btn-success btn-sm" href="{{ action('PaymentController@receivedPaymentEdit',$item->id)}}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->hasRole('admin'))
                                      <a class="btn btn-success btn-sm" href="{{ action('PaymentController@receivedPaymentEdit',$item->id)}}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    <a class="btn btn-primary btn-sm" href="{{ action('PaymentController@receivePaymentDetails',$item->id)}}"><i class="fa fa-eye"></i></a>
                                     @if(auth()->user()->hasRole('admin'))
                                    <a class="btn btn-danger btn-sm" 
                                    href="{{ action('PaymentController@receivePaymentDelete',$item->id)}}" onclick="return confirm(' you want to delete?');">
                                        <i class="fa fa-trash"></i></a>
                                     @endif   
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5"  class="text-right">Total=</th>
                                <th><strong>{{number_format($rows->sum('payment_amount'),2)}}</strong></th>
                            </tr>

                            <tr>
                                <th colspan="5"  class="text-right">Grand Total=</th>
                                <th><strong>{{number_format($grand,2)}}</strong></th>
                            </tr>
                            
                            
                        </tfoot>
                    </table>
                </div>
                <p>{!! urldecode(str_replace("/?","?",$rows->appends(Request::all())->render())) !!}</p>
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
