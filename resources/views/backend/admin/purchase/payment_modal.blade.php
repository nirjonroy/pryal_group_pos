@php

$due=($purchase->total_price - $purchase->payment_histories->sum('payment_amount'));
@endphp
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Details Of Purchase</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="POST" action="{{ route('admin.payment') }}">
        @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Purchase
                    <span class="font-weight-light" style="margin-right:5px; ">Invoice</span>
                    #<span id="invoice_no"> {{$purchase->invoice_no}}</span>
                    <br>
                    
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-row" style="margin-bottom: 5%;">
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:blue;">Total Amount</label> <br/>
                            <stron id="total_amount" style="color:blue;">{{$purchase->total_price}}</stron>
                 
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:green;">Total Paid Amount</label><br/>
                            <strong id="paid_amount" style="color:green;">{{$purchase->payment_histories->sum('payment_amount')}}</strong>
                            <input type="hidden" name="paid_amount" value="{{$purchase->payment_histories->sum('payment_amount')}}">
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
                            <input name="payment_amount" type="number" step="any" id="payment_amount" autofocus class="form-control" placeholder="Payment Amount" max="{{$due}}" value="">
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="form-row">
                       <div class="form-group col mb-0">
                            <label class="form-label">Note</label>
                            <textarea placeholder="Enter Note.." class="form-control" name="note"></textarea>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="purchase_id" id="id" value="{{$purchase->id}}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="submit" class="btn btn-primary" value="Pay Now">
                </div>
            </form>
    </div>
  </div>
</div>