@extends('layouts.backend.app')
@section('page_title') | Product Create @endsection
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

    <h4 class="font-weight-bold py-3 mb-0">Create Product</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Product List</a></li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h6 class="card-header">Product</h6>
                <div class="card-body">
                    <form action="{{ route('admin.product.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Product Unit<span class="red">*</span></label>
                            <select class="form-control" name="unit_id" required>
                                <option value="" hidden>Select A Unit</option>
                                @foreach($units as $unit)
                                <option value="{{$unit->id}}">{{ $unit->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('name'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label class="form-label">Product Name<span class="red">*</span></label>
                            <input value="{{ old('name') }}" name="name" type="text" class="form-control" placeholder="Product Name">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('name'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Purchase Price<span class="red">*</span></label>
                            <input value="{{ old('unit_price') }}" name="unit_price" type="number" class="form-control" placeholder="Purchase Price">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('unit_price'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('unit_price') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Sell Price<span class="red"></span></label>
                            <input value="{{ old('sell_price') }}" name="sell_price" type="number" class="form-control" placeholder="Sell Price">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('sell_price'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('sell_price') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Description<span></span></label>
                            <textarea name="description" id="description" cols="3" class="form-control"  rows="3"></textarea>

                            @if ($errors->has('description'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
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
