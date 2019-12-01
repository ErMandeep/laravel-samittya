@extends('backend.layouts.app')
@section('title', __('labels.backend.categories.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')
    {!! Form::model($branch, ['method' => 'PUT', 'route' => ['admin.branches.update', $branch->id], 'files' => true,]) !!}

    <div class="alert alert-danger d-none" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <div class="error-list">
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.categories.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.categories.index') }}"
                   class="btn btn-success">@lang('labels.backend.categories.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
            <div class="col-12 col-lg-12 form-group">
                {!! Form::label('title', trans('labels.backend.categories.fields.name').' *', ['class' => 'control-label']) !!}
                {!! Form::text('branch', old('branch'), ['class' => 'form-control', 'placeholder' => 'Enter Category Name', 'required' => false]) !!}

            </div>
            <div class="col-12 col-lg-12 form-group">
                {!! Form::label('City_id', trans('labels.backend.categories.fields.parent_id'), ['class' => 'control-label']) !!}
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
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

@push('after-scripts')

<script type="text/javascript">
    
    $('#city_id').select2({})

</script>

@endpush


