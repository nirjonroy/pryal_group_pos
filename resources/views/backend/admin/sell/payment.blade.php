<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Details Of Customer Invoice</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="POST" action="{{route('sell_paymentUpdate', [$sell->id])}}">

        @csrf
                <div class="modal-header">
                    <h5 class="modal-title">customer
                    <span class="font-weight-light" style="margin-right:5px; ">{{ $sell->customer->name}}</span>
                    #<span id="invoice_no"> {{ $sell->invoice_no}} </span>
                    <br>
                    
                    </h5>
                    
                    
                </div>
                <div class="modal-body">
                    <div class="form-row" style="margin-bottom: 5%;">
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:blue;">Total Amount</label> <br/>
                            <stron id="total_amount" style="color:blue;"> {{ $sell->total_price}}</stron>
                 
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:green;">Total Paid Amount</label><br/>
                            <strong id="paid_amount" style="color:green;">{{ $sell->payments->sum('total_price')}}</strong>
             
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:red;"><strong>Total Due Amount</strong></label><br/>
                            <h4><strong id="" style="color:red;">{{ $sell->total_price - $sell->payments->sum('total_price')}}</strong></h4>
                            <!--<input type="hidden" name="due" value="{{ $sell->total_price - $sell->payments->sum('total_price')}}" id="due">-->
                  
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        
                        <div class="form-group col mb-0">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                              <option value="" hidden>Select A Type</option>
                                @foreach ($payment_methods as $item)
                                <option  value="{{ $item->id }}">{{ $item->method }}</option>
                                @endforeach
                            </select>
                            <div class="clearfix"></div>
                        </div>
                
                        <div class="form-group col mb-0">
                            <label class="form-label">Payment Amount</label>
                            <input name="payment_amount" type="number" min="0" max="{{ $sell->total_price - $sell->payments->sum('total_price')}}" value="{{ $sell->total_price - $sell->payments->sum('total_price')}}" id=""  class="form-control" placeholder="Payment Amount" required value="">
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
                <input type="hidden" name="customer_id" id="id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="submit" class="btn btn-primary" value="Pay Now">
                </div>
            </form>
    </div>
  </div>
</div>