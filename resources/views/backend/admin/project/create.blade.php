@extends('layouts.backend.app')
@section('page_title') | Project Create @endsection
@push('css')
<style>
    .red{color:red;}
    .gray{color:gray;}
</style>
<link rel="stylesheet" href="{{asset('backend/links')}}/assets/libs/bootstrap-datepicker/bootstrap-datepicker.css">
<link rel="stylesheet" href="{{asset('backend/links')}}/assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
<link rel="stylesheet" href="{{asset('backend/links')}}/assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css">
<link rel="stylesheet" href="{{asset('backend/links')}}/assets/libs/timepicker/timepicker.css">
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Create Project</h4>
    <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="feather icon-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.project.index') }}">Project List</a></li>
        </ol>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h6 class="card-header">Project</h6>
                <div class="card-body">
                    <form action="{{ route('admin.project.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Project Name<span class="red">*</span></label>
                            <input value="{{ old('name') }}" name="name" type="text" class="form-control" placeholder="Project Name">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('name'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company Name<span class="red">*</span></label>
                            <select name="company_id" id="" class="form-control select2">
                                <option value="" hidden>Please Select a Company</option>
                                @foreach ($companies as $item)
                                <option {{ old('company_id') ==  $item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('company_id'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('company_id') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                         <div class="form-group">
                            <label class="form-label">Partner Name (optional)<span class="red">*</span></label>
                            <select name="project_partner" id="" class="form-control select2">
                                <option value="" hidden>Please Select a partner *(optional)</option>
                                @foreach ($partners as $item)
                                <option {{ old('project_partner') ==  $item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('project_partner'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('project_partner') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>


                        <div class="form-group">
                            <label class="form-label">Project Type<span class="red">*</span></label>
                            <select name="project_type_id" id="" class="form-control select2">
                                <option value="" hidden>Please Select a Project Type</option>
                                @foreach ($types as $item)
                                <option {{ old('project_type_id') ==  $item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('project_type_id'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('project_type_id') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>


                        <div class="form-group">
                            <label class="form-label">Project Value<span class="red">*</span> <small>(Amount)</small></label>
                            <input value="{{ old('project_value') }}" name="project_value" type="number" step="any" class="form-control" placeholder="Project Value">
                            <small class="gray">Fill out the field</small> <br/>
                            @if ($errors->has('project_value'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('project_value') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Date<span></span></label>
                            <input type="date" name="start_date" class="form-control" >

                            @if ($errors->has('start_date'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('start_date') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date<span></span></label>
                            <input type="date" name="end_date" class="form-control" >

                            @if ($errors->has('end_date'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('end_date') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
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
<script src="{{asset('backend/links')}}/assets/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="{{asset('backend/links')}}/assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
<script src="{{asset('backend/links')}}/assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js"></script>
<script src="{{asset('backend/links')}}/assets/libs/timepicker/timepicker.js"></script>
<script>
    $(function(){
        $('#datepicker-base-one').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            language: "de"
        });
    });
    $(function(){
        $('#datepicker-base-two').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            language: "de"
        });
    });
</script>
@endpush
@endsection
