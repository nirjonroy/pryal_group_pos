@php

$due=($project->project_value-$project->projectPayment->sum('payment_amount'));
@endphp
<div class="col-md-12">
    <h5 class="modal-title" id="exampleModalLabel">Details Of Project</h5>
    <form method="POST" action="{{ action('Backend\Admin\Project\ProjectController@projectPayment') }}">

        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Project
            <span class="font-weight-light" style="margin-right:5px; ">Name</span>
            #<span id="invoice_no"> {{$project->name}}</span>
            <br>
            
            </h5>
        </div>
            <div class="form-row" style="margin-bottom: 5%;">
                <div class="form-group col mb-0">
                    <label class="form-label" style="color:blue;">Total Amount</label> <br/>
                    <stron id="total_amount" style="color:blue;">{{$project->project_value}}</stron>
                    <input name="total_amount" type="hidden" id="total_amount_value" value="" class="form-control">
                    <div class="clearfix"></div>
                </div>
                <div class="form-group col mb-0">
                    <label class="form-label" style="color:green;">Paid Amount</label><br/>
                    <strong id="paid_amount" style="color:green;">{{$project->projectPayment->sum('payment_amount')}}</strong>
     
                    <div class="clearfix"></div>
                </div>
                <div class="form-group col mb-0">
                    <label class="form-label" style="color:red;"><strong>Due Amount</strong></label><br/>
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
               @if(auth()->user()->hasRole('admin'))
                <div class="form-group col mb-0">
                     <label class="form-label">Add Date</label>
                    <input type="date" name="date" class="form-control">
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
                    <input type="date" name="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                    <div class="clearfix"></div>
                </div>
                @endif
            </div>

            <div class="form-row">
               <div class="form-group col mb-0">
                    <label class="form-label">Payment Amount</label>
                    <input name="payment_amount" type="number" step="any" id="payment_amount" autofocus class="form-control" placeholder="Payment Amount"  required value="">
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
        <input type="hidden" name="project_id" id="id" value="{{$project->id}}">
        <input type="hidden" name="company_id" id="id" value="{{$project->company_id}}">
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Receive Now">
        </div>
    </form>
</div>