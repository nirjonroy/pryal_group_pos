@extends('layouts.backend.app')
@section('page_title')

@push('css')
<style type="text/css">

</style>
@endpush
@section('content')
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    <h4 class="font-weight-bold py-3 mb-0">Last 5 Day's Bank Statement Report's</h4>


    <div class="row">
        <div class="col-md-12">
        
            <div class="card">
                <div class="card-header no-print" >
                    <div class="col-md-12">
                    
                </div>
                
                    <a class="btn btn-sm btn-info no-print" onclick="imprimir()">print</a>
                    <a class="btn btn-sm btn-success" id="btnExport">Excel Export</a>
                </div>

                <div class="card-body" >
                    <div class="col-sm-6 pb-4">
                            @include('info.info')
                    </div>

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
                                    $total_in=$hand;
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
                                    <td style="color:red">{{number_format(abs($total_in - $out),2)}}</td>
                                    
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th colspan="4" class="text-right" style="color:red">Total = </th>
                                <th>{{ number_format($total_in,2) }}</th>
                                <th>{{ number_format($out,2) }}</th>
                                <th>{{ number_format(abs($total_in - $out),2) }}</th>
                            </tfoot>

                    
                           
                            
                        </table> 
                    </div>

                </div>


            </div>
           
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
