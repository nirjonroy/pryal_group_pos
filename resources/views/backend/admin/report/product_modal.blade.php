<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">{{$purchase->invoice_no}} Details Of Purchase</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <h4>Product List</h4>
      <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                              <th>SL</th>
                              <th>Invoice</th>
                              <th>Product</th>
                              <th>SKu</th>
                              <th>Description</th>
                              <th>Quantity</th>
                              <th>Unit Price</th>
                              <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->purchaseDetails as $details)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{$details->invoice_no}}</td>
                                    <td>{{$details->products->name}}</td>
                                    <td>{{$details->products->sku}}</td>
                                    <td>{{$details->products->description}}</td>
                                    <td>{{$details->quantity}}</td>
                                    <td>{{$details->unit_price}}</td>
                                    <td>{{$details->total_price}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>