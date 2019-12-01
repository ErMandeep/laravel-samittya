@extends('backend.layouts.app')
@section('title',  'State | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Add State</h3>
            <div class="float-right">
                <a href="{{ route('admin.states.index') }}"
                   class="btn btn-success">View States</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.states.store']]) !!}

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('title', trans('labels.backend.tags.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('state', old('state'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.tags.fields.name'), 'required' => false]) !!}

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

