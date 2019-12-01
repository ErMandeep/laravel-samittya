@extends('backend.layouts.app')
@section('title', 'State | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endpush
@section('content')
    {!! Form::model($states, ['method' => 'PUT', 'route' => ['admin.states.update', $states->id], ]) !!}

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
                <a href="{{ route('admin.states.index') }}"
                   class="btn btn-success">View Cities</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
            <div class="col-12 col-lg-4 form-group">
                {!! Form::label('title', trans('labels.backend.tags.fields.name').' *', ['class' => 'control-label']) !!}
                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Enter Category Name', 'required' => true]) !!}

            </div>
            <div class="col-12 form-group text-center">

                {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn mt-auto  btn-danger']) !!}
            </div>
        </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection



