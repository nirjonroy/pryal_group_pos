@extends('layouts.backend.app')
@section('page_title', '| Bank Statement') 
@push('css')
<style type="text/css">

</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <h4 class="font-weight-bold py-3 mb-0">Bank Statement Report</h4>


    <div class="row">
        <div class="col-md-12">
        
            <div class="card">
                <div class="card-header no-print" >
                    <div class="col-md-12">

                    <form autocomplete="off">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>All data:</label>
                                <select class="form-control" name="alldata" onchange="this.form.submit()">
                                    
                                   
                                    <option value="" hidden>Select</option>
                                    <option value="alldata" {{(request()->alldata=='alldata')?'selected':''}}>Get All Data</option>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label>Date From</label>
                                <input type="date" name="start_date" class="form-control" placeholder="yyyy-mm-dd" value="{{request()->start_date ? request()->start_date :''}}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Date To</label>
                                <input type="date" name="end_date" class="form-control" placeholder="yyyy-mm-dd" value="{{request()->end_date ? request()->end_date:''}}">
                            </div>
                            <div class="form-group col-md-3">
                               <br><br> <input type="submit"  class="btn btn-primary btn-sm" value="SUBMIT">
                               <a class="btn btn-info btn-sm" href="{{ route('statement') }}">Refresh</a>
                            </div>
                        </div>  
                    </form>
                    
                </div>
                
                    <a class="btn btn-sm btn-info" onclick="imprimir()">print</a>
                    <a class="btn btn-sm btn-info no-print" id="btnExport">Excel Export</a>
                 
                </div>

                <div class="card-body" >
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 pb-4">
                                        @include('info.info')
                                </div>
                            
                                <div class="col-md-6 pb-4">
                                    <table class="table">
                                        <tr>
                                            <th style="color:red">
                                                Total Cash In Hand
                                            </th>
                                            <th>
                                                <strong>
                                                {{number_format($totals->where('type','in')->sum('amount')-$totals->where('type','out')->sum('amount'),2)}}
                                               </strong>
                                            </th>
                                        </tr>
            
                                        <tr>
                                            <th style="color:red">Total=</th>
                                            <th>{{number_format($banks->where('type','in')->sum('amount'),2)}}</th>
                                            <th>{{number_format($banks->where('type','out')->sum('amount'),2)}}</th>
                                            
                                            <th>{{number_format($banks->where('type','in')->sum('amount')-$banks->where('type','out')->sum('amount'),2)}}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                             <div class="card-datatable table-responsive">
                                <table class="datatables-demo table table-striped table-bordered" id="table">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Bank Name</th>
                                            <th>Description Of Payment</th>
                                            <th>Cash In</th>
                                            <th>Cash Out</th>
                                            <th>Balance</th>
                                            <th>Remark</th>
                                            
        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_in=0;
                                            $out=0;
                                        @endphp
                                        @foreach($banks as $bank)
        
                                        @php
                                            $total_in +=$bank->type=='in'?$bank->amount:0;
                                            $out +=$bank->type=='out'?$bank->amount:0;
                                        @endphp
                                        <tr>
                                            <td>{{$loop->index+1}}</td>
                                            <td>{{date('d.m.Y', strtotime($bank->created_at))}}</td>
                                            <td>{{$bank->bank_name}}</td>
                                            <td>{{$bank->note}}</td>
                                            <td>{{$bank->type=='in'?$bank->amount:0}}</td>
                                            <td>{{$bank->type=='out'?$bank->amount:0}}</td>
                                            <td style="color:red">{{$bank->hand}}</td>
                                            
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
        
                                    <tfoot>
                                        <th colspan="4" class="text-right" style="color:red">
                                            Total Cash In Hand
                                        </th>
                                        <th>
                                            <strong>
                                            {{number_format($totals->where('type','in')->sum('amount')-$totals->where('type','out')->sum('amount'),2)}}
                                           </strong>
                                        </th>
                                    </tfoot>
        
        
                                    <tfoot>
                                        <th colspan="4" class="text-right" style="color:red">Total=</th>
                                        <th>{{number_format($banks->where('type','in')->sum('amount'),2)}}</th>
                                        <th>{{number_format($banks->where('type','out')->sum('amount'),2)}}</th>
                                        
                                        <th>{{number_format($banks->where('type','in')->sum('amount')-$banks->where('type','out')->sum('amount'),2)}}</th>
                                    </tfoot>
                                   
                                    
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p>{!! urldecode(str_replace("/?","?",$banks->appends(Request::all())->render())) !!}</p>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<script type="text/javascript">

 $(document).ready(function(){
    $("#btnExport").click(function() {
        let table = document.getElementsByTagName("table");
        TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
           name: `export.xls`, // fileName you could use any name
           sheet: {
              name: 'Sheet 1' // sheetName
           }
        });
    });
});

</script> 
@endpush
@endsection
