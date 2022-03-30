@extends('layouts.backend.app')
@section('page_title') 
@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    @include('layouts.backend.partial.success_error_status_message')

    <h4 class="font-weight-bold py-3 mb-0">Supplier Payment List</h4>

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
                        
                        <div class="col-md-3">
                            <label>Select Type</label>
                            <select name="type_id" class="form-control" onchange="this.form.submit()">
                                <option value="" {{request()->type_id=='' ?'selected':''}}>All Type</option>
                                @foreach($types as $type)
                                <option value="{{$type->id}}" {{request()->type_id==$type->id ?'selected':''}}>{{$type->name}}</option>
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

                        <div class="form-group col-md-1">
                            <br><br><input type="submit" class="btn btn-primary btn-sm" value="submit">
                        </div>

                        <div class="form-group col-md-1">
                            <br><br><a class="btn btn-info btn-sm" href="{{ action('PaymentController@supplierPaymentList')}}">Refresh</a>

                          
                        </div>
                        <div class="form-group " style="margin-right:5px;">
                           <br><br>
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

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Purchase Invoice</th>
                                <th>Supplier</th>
                                <th>Payment Method</th>
                                <th>Payment Amount </th>
                                <th>Description of Payment</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $key=>$item)
                            <tr>
                                <td> {{$key+ $rows->firstItem()}}</td>
                                <td>{{date('d.m.Y', strtotime($item->created_at))}}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{  $item->suppliers->name }}</td>
                                <td> {{  $item->method->method }}</td>
                                <td>{{  $item->total_price }}</td>
                                <td>
                                    {{ $item->description }}

                                </td>
                                <td class="no-print">
                                    
                                    @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                    
                                       <a class="btn btn-success btn-sm" href="{{ action('PaymentController@supplierPaymentEdit',$item->id)}}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->hasRole('admin'))
                                      <a class="btn btn-success btn-sm" href="{{ action('PaymentController@supplierPaymentEdit',$item->id)}}"><i class="fa fa-edit"></i></a>
                                    @endif
                                    <a class="btn btn-primary btn-sm" href="{{ action('PaymentController@supplierPaymentDetails',$item->id)}}"><i class="fa fa-eye"></i></a>
                                     @if(auth()->user()->hasRole('admin'))
                                    <a class="btn btn-danger btn-sm" 
                                    href="{{ action('PaymentController@supplierPaymentDelete',$item->id)}}" onclick="return confirm(' you want to delete?');">
                                        <i class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total = </th>
                                <th><strong>{{number_format($rows->sum('total_price'),2)}}</strong></th>
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
</div>
@endsection
