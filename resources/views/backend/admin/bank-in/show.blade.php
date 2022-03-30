@extends('layouts.backend.app')
@section('page_title') | Show Expense @endsection
@push('css')
<style>

</style>
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Purchase</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.expense.create') }}">Expense Create</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.expense.index') }}">Expense List</a></li>
        </ol>
    </div>



    <div class="row">
       <div class="col-md-12">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-sm-6 pb-4">
                            <div class="media align-items-center mb-4">
                            <a href="index.html" class="navbar-brand app-brand demo py-0 mr-4">
                            <span class="app-brand-logo demo">
                            <img src="{{ asset('backend/links') }}/assets/img/logo-dark.png" alt="Brand Logo" class="img-fluid">
                            </span>
                            <span class="app-brand-text demo font-weight-bold text-dark ml-2">{{ config('app.name') }}</span>
                            </a>
                            </div>
                            <div class="mb-1">
                                Office address
                            </div>
                            <div class="mb-1">Glendale, CA 91203, USA</div>
                        </div>

                        <div class="col-sm-6 text-right pb-4">
                            <h6 class="text-big text-large font-weight-bold mb-3">Expense</h6>
                            <div class="mb-1"> Date : {{ date('d-m-Y',strtotime($expense->created_at)) }}
                            <strong class="font-weight-semibold"></strong>
                            </div>
                            <div>Created :
                                <strong class="font-weight-semibold">
                                    {{ $expense->created_by?$expense->createdBy->name:'' }}
                                </strong>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4">

                    <div class="row">
                        <div class="col-sm-6 col-md-6 mb-4">
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

                        <div class="col-sm-6  col-md-6  mb-4">
                            <div class="pull-right" style="margin-right:5px;">
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
                                        Expense Title
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
                                            {{ $item->expense_title }}
                                        </strong>
                                    </td>
                                    <td class="py-3">
                                    <strong>
                                        {{ $item->description }}
                                    </strong>
                                    </td>
                                    <td class="py-3">
                                        <strong>
                                            {{ date('d-m-Y',strtotime($item->expense_date)) }}
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
               {{--   <div class="card-footer text-right">
                    <a href="pages_invoice-print.html" target="_blank" class="btn btn-default"><i class="ion ion-md-print"></i>&nbsp; Print</a>
                    <button type="button" class="btn btn-primary ml-2"><i class="ion ion-ios-paper-plane"></i>&nbsp; Send</button>
                </div>  --}}
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
