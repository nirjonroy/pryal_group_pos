@extends('layouts.backend.app')
@section('page_title') | Product Update @endsection
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
                    <form action="{{ route('admin.product.update',$product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                         <div class="form-group">
                            <label class="form-label">Product Unit<span class="red">*</span></label>
                            <select class="form-control" name="unit_id" required>
                                <option value="" hidden>Select A Unit</option>
                                @foreach($units as $unit)
                                <option value="{{$unit->id}}" {{$product->unit_id==$unit->id ?'selected':''}}>{{ $unit->name}}</option>
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
                            <input value="{{ $product->name ?? old('name') }}" name="name" type="text" class="form-control" placeholder="Product Name">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('name'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description<span></span></label>
                            <textarea name="description" id="description" cols="3" class="form-control"  rows="3">{{ $product->description }}</textarea>

                            @if ($errors->has('description'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        
                         <div class="form-group">
                            <label class="form-label">Purchase Price<span class="red"></span></label>
                            <input value="{{ $product->unit_price }}" name="unit_price" type="text" class="form-control" placeholder="Purchase Price">
                           
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Sell Price<span class="red"></span></label>
                            <input value="{{ $product->sell_price }}" name="sell_price" type="text" class="form-control" placeholder="sell Price">
                           
                        </div>

                        {{--
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Password">
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label w-100">File input</label>
                                <input type="file">
                                <small class="form-text text-muted">Example block-level help text here.</small>
                            </div>
                            <div class="form-group">
                                <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input">
                                <span class="custom-control-label">Check this custom checkbox</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-check">
                                <input class="form-check-input" type="checkbox" checked>
                                <span class="form-check-label">Check me out</span>
                                </label>
                            </div>
                        --}}
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
