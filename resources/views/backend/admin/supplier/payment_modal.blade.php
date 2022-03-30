@php

$due=(($supplier->purchase->sum('total_price') + $supplier->stock_purchase->sum('total_price')) - $supplier->purchaseStockpayment->sum('total_price'));
@endphp

<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Details Of Project</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="POST" action="{{ action('Backend\Admin\Supplier\SupplierController@purchasePayment') }}">

        @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Supplier
                    <span class="font-weight-light" style="margin-right:5px; ">Name</span>
                    #<span id="invoice_no"> {{$supplier->name}}</span>
                    <br>
                    
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="margin-bottom: 5%;">
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:blue;">Total Amount</label> <br/>
                            <stron id="total_amount" style="color:blue;">{{$supplier->purchase->sum('total_price') + $supplier->stock_purchase->sum('total_price')}}</stron>
                 
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:green;">Total Paid Amount</label><br/>
                            <strong id="paid_amount" style="color:green;">{{$supplier->purchaseStockpayment->sum('total_price')}}</strong>
             
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:red;"><strong>Total Due Amount</strong></label><br/>
                            <h4><strong id="due_amount" style="color:red;">{{$due}}</strong></h4>
                            <input type="hidden" name="due" value="{{$due}}" id="due">
                  
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col mb-0">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                              <option value="" hidden>Select A Type</option>
                                @foreach ($methods as $item)
                                <option  value="{{ $item->id }}">{{ $item->method }}</option>
                                @endforeach
                            </select>
                            <div class="clearfix"></div>
                        </div>
                
                        <div class="form-group col mb-0">
                            <label class="form-label">Payment Amount</label>
                            <input name="payment_amount" type="number" step="any" min="1" max="{{ $due}}" value="{{$due}}" id="payment_amount" autofocus class="form-control" placeholder="Payment Amount" required >
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        @if(auth()->user()->hasRole('admin'))
                         <div class="form-group col mb-0">
                            <label class="form-label">Add Date</label>
                            <input name="date" type="date" class="form-control">
                            <div class="clearfix"></div>
                        </div>
                        @else
                          <?php
                        $timezone = "Asia/Colombo";
                        date_default_timezone_set($timezone);
                        $today = date("Y-m-d");
                       ?>
                       <div class="form-group col mb-0">
                            <label class="form-label">Add Date</label>
                                <input type="date" name="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" required id="date" readonly>
                           <div class="clearfix"></div>
                        </div>
                        @endif


                       

                       <div class="form-group col mb-0">
                            <label class="form-label">Note</label>
                            <textarea placeholder="Enter Note.." class="form-control" name="note"></textarea>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="supplier_id" id="id" value="{{$supplier->id}}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="submit" class="btn btn-primary" value="Pay Now">
                </div>
            </form>
    </div>
  </div>
</div>