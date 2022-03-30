
<!-- Modal -->
<div class="modal-dialog" role="document">
	<form method="post" action="{{route('admin.updateStatus')}}">
		@csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update status of <b style="color: blue">{{$project->name}}</b> Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<label>Status :</label>
		    <input type="hidden" name="id" value="{{$project->id}}">
		    <select class="form-control" name="status">
		        <option value="" hidden > Select A Status</option>
		        @foreach($status as $key=>$item)
		        <option value="{{$item}}" {{$project->working_status==$item ?'selected' :''}}>{{$key}} </option>
		        @endforeach
		    </select>
        </div>

        <div class="form-group">
        	<label>Date :</label>
            @if(auth()->user()->hasRole('admin'))
        	<input type="date" name="date" class="form-control" value="{{$project->date}}" required>
          @else
          <?php
          $timezone = "Asia/Colombo";
          date_default_timezone_set($timezone);
          $today = date("Y-m-d");
        ?>

          <input type="date" name="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" readonly>
          @endif
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="Submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
</form>
</div>
