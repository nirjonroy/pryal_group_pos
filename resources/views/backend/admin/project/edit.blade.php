@extends('layouts.backend.app')
@section('page_title') | Project Update @endsection
@push('css')
<style>
    .red{color:red;}
    .gray{color:gray;}
</style>
@endpush

@section('content')
<!--########################################################################-->
<div class="container-fluid flex-grow-1 container-p-y">
    @include('layouts.backend.partial.success_error_status_message')
<!--########################################################################-->

    <h4 class="font-weight-bold py-3 mb-0">Update Project</h4>
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
                    <form action="{{ route('admin.project.update',$project->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Project Name<span class="red">*</span></label>
                            <input value="{{ $project->name ?? old('name') }}" name="name" type="text" class="form-control" placeholder="Project Name">
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
                                <option value="">Please Select a Company</option>
                                @foreach ($companies as $item)
                                <option {{ $project->company_id ==  $item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <option value="">Please Select a Partner</option>
                                @foreach ($partners as $item)
                                <option {{ $project->project_partner ==  $item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <option {{ $project->project_type_id ==  $item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
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
                            <input value="{{ $project->project_value ?? old('project_value') }}" name="project_value" type="number" step="any" class="form-control" placeholder="Project Value">
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
                            <input type="date" value="{{ $project->start_date }}" name="start_date" class="form-control">

                            @if ($errors->has('start_date'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('start_date') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date<span></span></label>
                            <input value="{{ $project->end_date }}" type="date" name="end_date" class="form-control">

                            @if ($errors->has('end_date'))
                            <span class="red" role="alert">
                                <strong>{{ $errors->first('end_date') }}</strong>
                            </span>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                       
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

@endsection
