<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Details Of Project :- {{$project->name}}</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="card-datatable table-responsive">
                    <table class="datatables-demo table table-striped table-bordered">
                        <thead>
                            <tr>
                              <th>SL</th>
                              <th>Pay at</th>
                              <th>Payment Method</th>
                              <th>Payment Amount</th>
                              <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->projectPayment as $details)
                                <tr>
                                    <td> {{ $loop->index+1 }}</td>
                                    <td>{{date('d.m.Y', strtotime($details->created_at ))}}</td>
                                    <td>{{$details->method->method}}</td>
                                    <td>{{$details->payment_amount}}</td>
                                    <td>{{$details->note}}</td>
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