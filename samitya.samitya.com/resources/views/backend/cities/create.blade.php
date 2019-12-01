@extends('backend.layouts.app')
@section('title',  'Cities | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Add City</h3>
            <div class="float-right">
                <a href="{{ route('admin.cities.index') }}"
                   class="btn btn-success">View Cities</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.cities.store']]) !!}

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('title', trans('labels.backend.tags.fields.name').' *', ['class' => 'control-label']) !!}
                            {!! Form::text('city', old('city'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.tags.fields.name'), 'required' => false]) !!}

                        </div>

                         <div class="col-12 col-lg-4 form-group">
                            {!! Form::label('State', 'State', ['class' => 'control-label']) !!}
                                    {!! Form::select('state_id', $states,  (request('state_id')) ? request('state_id') : old('state_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'state_id']) !!}

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
    
    $('#state_id').select2({});

</script>

@endpush
