@extends('layouts.backend.app')
@section('page_title') | Product Details @endsection
@push('css')
<style>

</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
       <div class="col-md-12">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-sm-12">
                            <div>
                               <b> Name </b> :  {{ $product->name }}
                            </div><br>
                            <div>
                                <b>SKU</b> :  {{ $product->sku }}
                            </div><br>
                            
                            <div><b>Product Purchase Price  </b>: {{ $product->unit_price }}</div>
                            <div><b>Product Sell Price  </b>: {{ $product->sell_price }}</div>
                            <div><b>Product Description </b>: {{ $product->description }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
