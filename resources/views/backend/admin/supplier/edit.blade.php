@extends('layouts.backend.app')
@section('page_title') | Supplier Update @endsection
@push('css')
<style>
    .red{color:red;}
    .gray{color:gray;}
</style>
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Update Supplier</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.supplier.index') }}">Supplier List</a></li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h6 class="card-header">Supplier</h6>
                <div class="card-body">
                    <form action="{{ route('admin.supplier.update',$supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Supplier Name<span class="red">*</span></label>
                            <input value="{{ $supplier->name ?? old('name') }}" name="name" type="text" class="form-control" placeholder="Supplier Name">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('name'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Contract Phone<span class="red">*</span></label>
                            <input value="{{ $supplier->contract_phone ??  old('contract_phone') }}" name="contract_phone" type="text" class="form-control" placeholder="Supplier Phone">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('contract_phone'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('contract_phone') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contract Address<span></span></label>
                            <textarea name="address" id="address" cols="3" class="form-control"  rows="3">{{  $supplier->address }}</textarea>

                            @if ($errors->has('address'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                         <div class="form-group">
                            <label class="form-label">Description<span></span></label>
                            <textarea name="note" id="address" cols="3" class="form-control"  rows="3">{{$supplier->note}}</textarea>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Supplier Type<span class="red">*</span></label>
                            <select name="type_id" class="form-control">
                                <option value="" hidden>Select A Type</option>
                                @foreach($types as $type)
                                <option value="{{$type->id}}" {{$type->id==$supplier->type_id ?'selected':''}}>{{$type->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('type_id'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('type_id') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
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

@endpush
@endsection
