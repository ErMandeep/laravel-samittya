@extends('backend.layouts.app')
@section('title', 'Branches | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Create Branch</h3>
            <div class="float-right">
                <a href="{{ route('admin.branches.index') }}"
                   class="btn btn-success">View Branches</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.branches.store'], 'files' => true,]) !!}

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-12 form-group">
                            {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('branch', old('branch'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.categories.fields.name'), 'required' => false]) !!}
                        </div>

                        <div class="col-12 col-lg-12 form-group">
                            {!! Form::label('City_id', 'City*', ['class' => 'control-label']) !!}
                            {!! Form::select('city_id', $city,  (request('city_id')) ? request('city_id') : old('city_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'city_id']) !!}
                        </div>

                        <div class="col-12 col-lg-12 form-group">
                            {!! Form::label('desc', 'Description *', ['class' => 'control-label']) !!}
                            {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' =>'Description', 'required' => false]) !!}
                        </div>

                        <div class="col-12 form-group text-center">
                            {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')

<script type="text/javascript">
	
	$('#city_id').select2({})

</script>

@endpush
