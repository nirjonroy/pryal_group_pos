@extends('layouts.backend.app')
@section('page_title') | Purchase Wise Report @endsection
@section('content')
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <h4 class="font-weight-bold py-3 mb-0">Purchase Wise Report</h4>
   
    <div class="row" >
        <div class="col-md-12">
            <div class="card">
               <div class="col-md-12">
                    <form class="no-print">
                        <div class="row">

                            <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>Company Type:</label>
                                <select class="form-control" name="type_id" onchange="this.form.submit()">
                                    <option value="" {{request()->type_id=='' ?'selected':''}}>All</option>
                                    @foreach($comTypes as $type)
                                    <option value="{{$type->id}}" {{request()->type_id==$type->id ?'selected':''}}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>Company:</label>
                                <select class="form-control" name="company_id" onchange="this.form.submit()">
                                    <option value="" {{request()->company_id=='' ?'selected':''}}>All</option>
                                    @foreach($coms as $com)
                                    <option value="{{$com->id}}" {{request()->company_id==$com->id ?'selected':''}}>{{$com->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Project Status :</label>
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="" hidden="hidden">Select Stratus</option>
                                    <option value="0" {{request()->status=='0' ?'selected':''}}>Running</option>
                                    <option value="1" {{request()->status=='1' ?'selected':''}}>Complete</option>
                                    <option value="2" {{request()->status=='2' ?'selected':''}}>Work Done</option>
                                    <option value="3" {{request()->status=='3' ?'selected':''}}>Partner Investment</option>
                                    <option value="" {{request()->status=='' ?'selected':''}}>All</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Projects:</label>
                                <select class="form-control" name="project_id" onchange="this.form.submit()">
                                    <option value="" {{request()->project_id=='' ?'selected':''}}>All</option>
                                    @foreach($projects as $project)
                                    <option value="{{$project->id}}" {{request()->project_id==$project->id ?'selected':''}}>{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-3">
                                <label>Suppliers:</label>
                                <select class="form-control" name="supplier_id" onchange="this.form.submit()">
                                    <option value="" {{request()->supplier_id=='' ?'selected':''}}>All</option>
                                    @foreach($sups as $sup)
                                    <option value="{{$sup->id}}" {{request()->supplier_id==$sup->id ?'selected':''}}>{{$sup->name}}</option>
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
                                <br><br><a class="btn btn-info btn-sm" href="{{ action('Backend\ReportController@purchaseWise')}}">Refresh</a>
                            </div>
                            <div class="form-group col-md-1">
                                <br><br><a class="btn btn-sm btn-primary" onclick="imprimir()">Print</a>
                              
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

                <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Project Name</th>
                                <th>Company Name</th>
                                <th>Supplier Name</th>
                                <th>Description</th>
                                <th>Total Quantity</th>
                                <th>Total Price</th>
                                <th class="no-print">Action</th>
                           </tr>
                            
                        </thead>
                        <tbody>
                            @php
                                $pay=0;
                                $due=0;

                            @endphp
                            @foreach($purchases as $key=> $purchase)

                           
                                <tr>
                                    <td> {{$key+ $purchases->firstItem()}}</td>
                                    <td>{{date('d.m.Y', strtotime($purchase->created_at))}}</td>
                                    <td>{{$purchase->invoice_no}}</td>
                                    <td>{{$purchase->projects->name}}</td>
                                    <td>{{$purchase->projects->companies->name}}</td>
                                    <td>{{$purchase->suppliers->name}}</td>
                                    <td>{{$purchase->description}}</td>
                                    <td>{{$purchase->total_quantity}}</td>
                                    <td>{{$purchase->total_price}}</td>
                                    <td class="no-print">
                                        <!-- <a class="btn btn-info btn-sm" id="btn-modal" data-href="{{ action('Backend\ReportController@purchaseWiseProduct',$purchase->id)}}">
                                            Products
                                        </a> -->

                                        <a class="btn btn-sm btn-info no-print" href="{{ url('reports/purchase-wise-report-view',$purchase->id) }}">View
                                        </a>

                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <th colspan="6"></th>
                                <th colspan="" class="text-center"><strong>This Page Summery</strong></th>
                                <th><strong>{{number_format($purchases->sum('total_quantity'),2)}}</strong></th>
                                <th><strong>{{number_format($purchases->sum('total_price'),2)}}</strong></th>
                            </tr>

                           
                                
                          
                            <tr>
                                <th colspan="6"></th>
                                <th colspan="" class="text-center"><strong>Summery With Previous Pages</strong></th>
                                <th><strong>{{number_format($this_page_purchases->sum('total_quantity'),2)}}</strong></th>
                                <th><strong>{{number_format($this_page_purchases->sum('total_price'),2)}}</strong></th>
                            </tr>
                            <tr>
                                <th colspan="6"></th>
                                <th colspan="" class="text-center"><strong>Total Summery</strong></th>
                                <th><strong>{{number_format($purchases_totals->sum('total_quantity'),2)}}</strong></th>
                                <th><strong>{{number_format($purchases_totals->sum('total_price'),2)}}</strong></th>
                            </tr>



                        </tbody>
                    </table>
                </div>

                <p>{!! urldecode(str_replace("/?","?",$purchases->appends(Request::all())->render())) !!}</p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade container" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div>
@push('js')
<script src="{{ asset('backend/links')}}/assets/js/ajax.js"></script>
<script src="{{ asset('backend/links')}}/assets/js/bootstrap.js"></script>

<script type="text/javascript">
     $(document).on( 'click', '#btn-modal', function(e){
        e.preventDefault();

        $.ajax({
            url: $(this).data("href"),
            dataType: "html",
            success: function(result){
                $('.container').html(result).modal('show');
            }
        });
    });
</script>
@endpush
@endsection
