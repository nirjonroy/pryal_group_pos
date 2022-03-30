<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Details Of supplier Invoice</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form method="POST" action="{{route('purchase_paymentUpdate', [$purchase->id])}}">

        @csrf
                <div class="modal-header">
                    <h5 class="modal-title">supplier
                    <span class="font-weight-light" style="margin-right:5px; ">{{ $purchase->suppliers->name}}</span>
                    #<span id="invoice_no"> {{ $purchase->invoice_no}} </span>
                    <br>
                    
                    </h5>
                    
                    
                </div>
                <div class="modal-body">
                    <div class="form-row" style="margin-bottom: 5%;">
                        <div class="form-group col mb-0">
                            @php
                                    $purPric = $purchase->total_price;
                                    $car_r = $purchase->car_rent;
                                    $bost_cost = $purchase->bosta_cost;
                                    $labour_cost = $purchase->labour_cost;
                                    $other_cost = $purchase->other_cost;
                                    $total_sum = $purPric + $car_r + $bost_cost + $labour_cost + $other_cost;
                                @endphp
                            
                            
                            <label class="form-label" style="color:blue;">Total Amount</label> <br/>
                            <stron id="total_amount" style="color:blue;"> {{ $total_sum }}</stron>
                 
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:green;">Total Paid Amount</label><br/>
                            <strong id="paid_amount" style="color:green;">{{ $purchase->payments->sum('total_price')}}</strong>
             
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group col mb-0">
                            <label class="form-label" style="color:red;"><strong>Total Due Amount</strong></label><br/>
                            <h4><strong id="" style="color:red;">{{ $total_sum - $purchase->payments->sum('total_price')}}</strong></h4>
                            <!--<input type="hidden" name="due" value="{{ $purchase->total_price - $purchase->payments->sum('total_price')}}" id="due">-->
                  
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
                            <input name="payment_amount" type="number" min="1"  max="{{ $total_sum - $purchase->payments->sum('total_price')}}" value="{{ $total_sum - $purchase->payments->sum('total_price')}}" id=""  class="form-control" placeholder="Payment Amount" required value="">
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
                <input type="hidden" name="supplier_id" id="id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id="submit" class="btn btn-primary" value="Pay Now">
                </div>
            </form>
    </div>
  </div>
</div>