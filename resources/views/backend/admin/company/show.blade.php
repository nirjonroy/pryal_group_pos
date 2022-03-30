@extends('layouts.backend.app')
@section('page_title') Home Page @endsection
@push('css')
<style>

</style>
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')

    <h4 class="font-weight-bold py-3 mb-0">Company Details</h4>



    <div class="container">
        <div class="col-md-12">
            <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Comanay Name</th>
                                <th>Contract Person</th>
                                <th>Contract Phone</th>
                                <th>Address</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{\Illuminate\Support\Str::limit($company->name, 300)}}
                                </td>
                                <td>
                                    {{\Illuminate\Support\Str::limit($company->contract_person, 300)}}
                                </td>
                                <td>
                                    {{ $company->contract_phone}}
                                </td>
                                <td>
                                    {{\Illuminate\Support\Str::limit($company->address, 300)}}
                                </td>
                                <td>{{ date('d.m.Y',strtotime($company->created_at))}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

</div>

@push('js')

@endpush
@endsection
