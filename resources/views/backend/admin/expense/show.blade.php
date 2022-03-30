@extends('layouts.backend.app')
@section('page_title') 
@push('css')
<style>

</style>
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y" id="print">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Expense Details</h4>
    <a class="btn btn-sm btn-primary no-print mb-3" onclick="imprimir()">Print</a>
    <div class="row">
       <div class="col-md-12">
            <div class="card" >
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-sm-6 pb-4">
                            @include('info.info')
                        </div>

                        <div class="col-sm-6 text-right pb-4">
                            <h6 class="text-big text-large font-weight-bold mb-3">Expense</h6>
                            <div class="mb-1"> Date : {{ date('d.m.Y',strtotime($expense->created_at)) }}
                            <strong class="font-weight-semibold"></strong>
                            </div>
                            <div>Created :
                                <strong class="font-weight-semibold">
                                    {{ $expense->created_by?$expense->createdBy->name:'' }}
                                </strong>
                            </div>

                            <div>Note :
                                <strong class="font-weight-semibold">
                                    {{ $expense->description }}
                                </strong>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4">

                    <div class="row mb-3" >
                        <div class="col-md-12">
                            
                        <div class="pull-left">
                            <div class="font-weight-bold mb-2">Company :</div>
                            <div>
                               <b> Name </b> : {{ $expense->companies->name }}
                            </div>
                            <div>
                                <b>Address</b> : {{ $expense->companies->address }}
                            </div>
                            <div><b>Phone </b>: {{ $expense->companies->contract_phone }}
                            </div>
                        </div>

                        
                            <div class="pull-right" style="margin-top:-80px;">
                                <div class="font-weight-bold mb-2 ">Project :</div>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th class="pr-3">Project Name :</th>
                                            <td>
                                                <strong>
                                                    {{ $expense->projects->name }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="pr-3">Project Value:</th>
                                            <td>
                                                <strong>
                                                    {{ $expense->projects->project_value }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                       
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="datatables-demo table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="py-3">
                                        Expense Type
                                    </th>
                                    <th class="py-3">
                                        Short Description
                                    </th>
                                    <th class="py-3">
                                        Expense Date
                                    </th>
                                    <th class="py-3">
                                        Total Price
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expense->expenseDetails as $item)
                                <tr>
                                    <td>#{{ $loop->index+1 }}</td>
                                    <td class="py-3">
                                        <strong>
                                            {{ $item->type->name }}
                                        </strong>
                                    </td>
                                    <td class="py-3">
                                    <strong>
                                        {{ $item->description }}
                                    </strong>
                                    </td>
                                    <td class="py-3">
                                        <strong>
                                            {{ date('d.m.Y',strtotime($item->expense_date)) }}
                                        </strong>
                                    </td>
                                    <td class="py-3">
                                        <strong class="finalTotal">
                                            {{ $item->total_price }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: right">Total</th>
                                    <th >
                                        <strong id="">
                                            {{ $expense->totalAmount() }}
                                        </strong>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-muted">

                    </div>
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
    <script>
        let total = 0;
        $('.finalTotal').each(function()
        {
            total += parseFloat($(this).text());
        })
        if(isNaN(total)) {
            total = 0;
        }
        $('#showTotal').text(total);
    </script>
@endpush
@endsection
