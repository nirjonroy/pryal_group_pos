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

    <h4 class="font-weight-bold py-3 mb-0">Expense List</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb no-print">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.expense.create') }}">Expense Create</a></li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card">
                 <form class="no-print">
                        <div class="row p-3">
                             <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                   <option value="" hidden>Select</option>
                                    <option value="alldata">Get All Data</option>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>Category:</label>
                                <select class="form-control" name="category_id" onchange="this.form.submit()">
                                    <option value="" {{request()->category_id=='' ?'selected':''}}>Expense Category</option>
                                    @foreach($cats as $com)
                                    <option value="{{$com->id}}" {{request()->category_id==$com->id ?'selected':''}}>{{$com->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Expense Type:</label>
                                <select class="form-control" name="expense_type_id" onchange="this.form.submit()">
                                    <option value="" {{request()->expense_type_id=='' ?'selected':''}}>Expense Type</option>
                                    @foreach($expense_types as $expense_type)
                                    <option value="{{$expense_type->id}}" {{request()->expense_type_id==$expense_type->id ?'selected':''}}>{{$expense_type->name}}</option>
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
                                <br><br><input type="submit" class="btn btn-primary btn-xs" value="submit">
                            </div>

                            <div class="form-group col-md-1">
                               <br><br><a class="btn btn-info btn-xs" href="{{ route('admin.expense.index')}}">Refresh</a>
                            </div>
                            <div class="form-group ">
                                  <br><br>
                                 <a id="btnExport" class="btn btn-sm btn-info no-print">Export to excel</a>
                               
                            </div>
                             <div class="form-group col-md-1">
                                <br><br><a class="btn btn-xs btn-primary" onclick="imprimir()">Print</a>
                            </div>
                        </div>

                        
                    </form>
                    
                    
                    <div class="col-sm-6 pb-4">
                    @include('info.info')
                </div>
                
                <div class="table-responsive">
                    <table class=" table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <form>
                                    <select name="shorting" onchange="this.form.submit()">
                                        <option value="asc" {{request()->shorting=='asc' ?'selected':''}}>asc</option>
                                        <option value="desc" {{request()->shorting=='desc' ?'selected':''}}>desc</option>
                                    </select>
                                    </form>
                                </th>
                                <th>Date</th>
                                <th>Expense Category</th>
                                <th>Expense Type</th>
                                
                                <th>Total Amount</th>
                                <th>Description</th>
                                <th class="no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_price=0;
                            @endphp
                            @foreach ($expenses as $key=>$item)
                            <tr>
                                <td>
                                    {{$key+ $expenses->firstItem()}}
                                </td>
                                <td>{{ date('d.m.Y',strtotime($item->expense_date)) }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>
                                    @php
                                        $detail_amount=0;
                                    @endphp
                                    @if(request()->expense_type_id)
                                    @foreach($item->expenseDetails()->with('type')->where('type_id',request()->expense_type_id)->get() as $detail)
                                    @php
                                        $detail_amount+=$detail->total_price;
                                    @endphp
                                    {{$detail->type->name}},
                                    @endforeach


                                    @else
                                    @foreach($item->expenseDetails as $detail)
                                    @php
                                        $detail_amount+=$detail->total_price;
                                    @endphp
                                    {{$detail->type->name}},
                                    @endforeach
                                    @endif
                                    
                                </td>
                                
                                <td>{{ number_format($detail_amount,2) }}</td>
                                <td>{{ $item->description }}</td>
                                <td class="no-print" style="width:10%;">
                                    <div class="btn-group" id="hover-dropdown-demo">
                                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-trigger="hover">Action</button>
                                        <div class="dropdown-menu">
                                            <a class=" btn btn-primary btn-sm" href="{{ route('admin.expense.show',$item->id) }}">View</a>
                                            
                                             @if(!auth()->user()->hasRole('admin') and ( date('Y-m-d') == date('Y-m-d', strtotime($item->created_at))))
                                                <a class=" btn btn-info btn-sm" href="{{ route('admin.expense.edit',$item->id) }}">Edit</a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin'))
                                             <a class="btn btn-info btn-sm" href="{{ route('admin.expense.edit',$item->id) }}">Edit</a>
                                            @endif
                                            
                                            @if(auth()->user()->hasRole('admin'))
                                            <form action="{{ route('admin.expense.destroy' , $item->id)}}" method="POST">
                                            <input name="_method" type="hidden" value="DELETE">
                                            {{ csrf_field() }}

                                            &nbsp;<input style="margin-top: 10px;" type="submit" value="delete" class="btn btn-sm btn-danger btn-sm" onclick="return confirm(' you want to delete?');">
                                        </form>
                                         @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $total_price+=$detail_amount;
                            @endphp
                            @endforeach
                        </tbody>

                        <tfoot>
                             <tr>
                                <th colspan="4" class="text-right"><strong>Total Page Summery = </strong></th>
                                <th><strong>{{number_format($total_price,2)}}</strong></th>
                            </tr>

                             <tr>
                                <th colspan="4" class="text-right"><strong>Total Summery =</strong></th>
                                <th><strong>{{number_format($total_summery,2)}}</strong></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <p>{!! urldecode(str_replace("/?","?",$expenses->appends(Request::all())->render())) !!}</p>
        </div>
    </div>

<!--########################################################################-->
<!--########################################################################-->
<!---main content page end div-->
</div>

@endsection
