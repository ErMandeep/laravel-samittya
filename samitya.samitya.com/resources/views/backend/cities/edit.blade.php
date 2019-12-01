@extends('backend.layouts.app')
@section('title', 'Cities | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')
    {!! Form::model($cities, ['method' => 'PUT', 'route' => ['admin.cities.update', $cities->id], ]) !!}

    <div class="alert alert-danger d-none" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="error-list">
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">@lang('labels.backend.tags.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.cities.index') }}"
                   class="btn btn-success">View Cities</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
            <div class="col-12 col-lg-4 form-group">
                {!! Form::label('title', trans('labels.backend.tags.fields.name').' *', ['class' => 'control-label']) !!}
                {!! Form::text('city', old('city'), ['class' => 'form-control', 'placeholder' => 'Enter Category Name', 'required' => true]) !!}

            </div>

            <div class="col-12 col-lg-4 form-group">
                {!! Form::label('State', 'State', ['class' => 'control-label']) !!}
                        {!! Form::select('state_id', $states,  (request('state_id')) ? request('state_id') : old('state_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'state_id']) !!}

            </div>
            <div class="col-12 form-group text-center">

                {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
            </div>
        </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

@push('after-scripts')

<script type="text/javascript">
    
    $('#state_id').select2({});

</script>

@endpush

