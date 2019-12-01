@extends('backend.layouts.app')

@section('title', 'Supervisor | '.app_name())

@section('content')
    {{ html()->form('POST', route('admin.supervisor.store'))->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Add Supervisor</h3>
            <div class="float-right">
                <a href="{{ route('admin.supervisor.index') }}"
                   class="btn btn-success">View Supervisors</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.email'))->class('col-md-2 form-control-label')->for('email') }}

                        <div class="col-md-10">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label('Phone')->class('col-md-2 form-control-label')->for('phone') }}

                        <div class="col-md-10">
                            {{ html()->text('phone')
                                ->class('form-control')
                                ->placeholder('Phone')
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.password'))->class('col-md-2 form-control-label')->for('password') }}

                        <div class="col-md-10">
                            {{ html()->password('password')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.password'))
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                            {{ html()->label(_('State'))->class('col-md-2 form-control-label')->for('state_id') }}
                        <div class="col-md-10 form-group">
                            {!! Form::select('state_id', $states,  (request('state_id')) ? request('state_id') : old('state_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'state_id']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                            {{ html()->label(_('City'))->class('col-md-2 form-control-label')->for('city_id') }}
                        <div class="col-md-10 form-group">
                            {!! Form::select('city_id', $city,  (request('city_id')) ? request('city_id') : old('city_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'city_id']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                            {{ html()->label(_('Branch'))->class('col-md-2 form-control-label')->for('branch_id') }}
                        <div class="col-md-10 form-group">
                            {!! Form::select('branch_id', $branch,  (request('branch_id')) ? request('branch_id') : old('branch_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'branch']) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(_('Description'))->class('col-md-2 form-control-label')->for('description') }}
                        <div class="col-md-10 form-group">
                            {!! Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => "Description"]) !!}
                        </div>
                    </div>

                    <div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.supervisor.index'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('buttons.general.crud.create')) }}
                        </div>
                    </div><!--col-->
                </div>
            </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

@push('after-scripts')
<script type="text/javascript">

   $('#supervisor').select2({
          placeholder: "Supervisor",
      });
    $('#city_id').select2({
          placeholder: "City",
      });
    $('#branch').select2({
          placeholder: "Branch",
      });
    $('#category_id').select2({
          placeholder: "Category",
      });
    $('#state_id').select2({
          placeholder: "State",
      });

    $('#state_id').change(function(){
        var state = $(this).val();

        $.ajax({
          type: "POST",
          url: "{{ route('admin.get_cities') }}",
          cache: true,
          data:  {
            "_token": "{{ csrf_token() }}",
            "id": state,
        },

          success: function(result){

             $("#city_id").html('').select2({data:  [
                    {
                      "id": "",
                      "text": ""
                    }, ]    });
             $("#city_id").select2({ placeholder: "City" , data:  result  });
             $("#branch").html('').select2({placeholder: "Branch"});

          }
        });
    })

    @if(old('state_id'))
        $.ajax({
          type: "POST",
          url: "{{ route('admin.get_cities') }}",
          cache: true,
          data:  {
            "_token": "{{ csrf_token() }}",
            "id": @php print_r(old('state_id')) @endphp,
        },

          success: function(result){
         
                $("#city_id").html('').select2({  data:  result  });

                if ($('#city_id').val() & $('#city_id').val() === "") {
                        var city_old = $('#city_id').val();      
                    }
                    else{
                         var city_old = '@php print_r(old('city_id')); @endphp'
                    }
                    $('#city_id').val(city_old);
                    $('#city_id').select2().trigger('change');
                    // $("#city_id").select2("val",city_old); 
          }
        });
    @endif

    $('#city_id').change(function(){
       if ($(this).val()) {
            var city = $(this).val();      
        }
        else{
             var city = '@php print_r(old('city_id')); @endphp'
        }
        $.ajax({
          type: "POST",
          url: "{{ route('admin.get_branches') }}",
          cache: true,
          data:  {
            "_token": "{{ csrf_token() }}",
            "id": city,
        },

          success: function(result){
             $("#branch").html('').select2({data:  result});    
             @if (old('branch_id')) 
                 var branch = @php print_r(old('branch_id')) @endphp;
                    $('#branch').val(branch);
                    $('#branch').select2().trigger('change');
                    // $("#branch").select2("val", branch); 
                 @endif        
          }
        });
    })






</script>


@endpush
