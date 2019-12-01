@extends('backend.layouts.app')
@section('title', 'Students | '.app_name())

@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css"/>

<style type="text/css">
     #submit{
    color: #ffffff !important;
    font-size: 18px;
    background: linear-gradient(90deg, rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
    padding: 10px 19px;
    border-radius: 50px;
    line-height: 30px;
    display: inline-block;
    transition: all 0.3s ease-in-out 0s;
    box-shadow: rgb(51, 51, 51) 0px 5px 5px -5px;
    border: 0;
    font-weight: bold;
    }
    .form-control {
    border-radius: 0;
    width: 32%;
    margin: 0 auto;
    }
    .select2-selection__rendered {
    display: block;
    width: 100%;
    height: calc(2.0625rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5 !important;
    color: #5c6873 !important;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #e4e7ea;
    }
    .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 0;
    border-radius: 0px;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #5c6873;
    }

</style>
   
@endpush
@section('content')



    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">Add Student</h3>
            <div class="float-right">
                <a href="{{ route('admin.offline-student.index') }}"
                   class="btn btn-success">View Students</a>

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.offline-student.store'], 'files' => true,]) !!}

                    <div class="row justify-content-center">


                        <div class="col-12 col-lg-12 form-group">
                            <!-- {!! Form::label('title', 'Date', ['class' => 'control-label']) !!} -->
                            {!! Form::text('joining_date', old('joining_date'), ['class' => 'form-control', 'placeholder' => 'Date', 'required' => true , 'id' => 'date' , 'autocomplete' => 'off']) !!}

                        </div>




                        <div class="col-12 col-lg-12 form-group">
                            <!-- {!! Form::label('title', 'Student Name', ['class' => 'control-label']) !!} -->
                            {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Student Name', 'required' => true]) !!}
                        </div>

                          @if(Auth::user()->isAdmin())
                            <div class="col-12 col-lg-4 form-group"></div>
                            <div class="col-12 col-lg-4 form-group">
                                <!-- {!! Form::label('City_id', 'City', ['class' => 'control-label']) !!} -->
                                {!! Form::select('state_id', $states,  (request('state_id')) ? request('state_id') : old('state_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'state_id']) !!}
                            </div>
                            <div class="col-12 col-lg-4 form-group"></div>

                        
                            <div class="col-12 col-lg-4 form-group"></div>
                            <div class="col-12 col-lg-4 form-group">
                                <!-- {!! Form::label('City_id', 'City', ['class' => 'control-label']) !!} -->
                                {!! Form::select('city_id', $city,  (request('city_id')) ? request('city_id') : old('city_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'city_id']) !!}
                            </div>
                            <div class="col-12 col-lg-4 form-group"></div>

                            <div class="col-12 col-lg-4 form-group"></div>
                            <div class="col-12 col-lg-4 form-group">
                            <!-- {!! Form::label('branch_id', 'Branch', ['class' => 'control-label']) !!} -->
                                {!! Form::select('branch_id', $branch,  (request('branch_id')) ? request('branch_id') : old('branch_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'branch']) !!}
                            </div>
                            <div class="col-12 col-lg-4 form-group"></div>
                          @endif

                            <div class="col-12 col-lg-4 form-group"></div>
                            <div class="col-12 col-lg-4 form-group">
                                <!-- {!! Form::label('City_id', 'City', ['class' => 'control-label']) !!} -->
                                {!! Form::select('category_id', $category,  (request('category_id')) ? request('category_id') : old('category_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'category_id']) !!}
                            </div>
                            <div class="col-12 col-lg-4 form-group"></div>

                            <div class="col-12 col-lg-4 form-group"></div>
                            <div class="col-12 col-lg-4 form-group">
                                <!-- {!! Form::label('City_id', 'City', ['class' => 'control-label']) !!} -->
                                {!! Form::select('fee_plan', array('' =>  '' ,'1' => '1 Month', '3' => '3 Month' ,'6' => '6 Month' , '12' => '12 Month') , old('fee_plan'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'fee_plan']) !!}
                            </div>
                            <div class="col-12 col-lg-4 form-group"></div>

                        <div class="col-12 col-lg-12 form-group">
                            <!-- {!! Form::label('title', 'Student Name', ['class' => 'control-label']) !!} -->
                            {!! Form::text('fees', old('fees'), ['class' => 'form-control', 'placeholder' => 'Fees', 'required' => true]) !!}
                        </div>

                        <div class="col-12 col-lg-12 form-group">
                            <!-- {!! Form::label('title', 'Student Name', ['class' => 'control-label']) !!} -->
                            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email id', 'required' => true]) !!}
                        </div>



                        <div class="col-12 col-lg-12 form-group">
                            <!-- {!! Form::label('title', 'Student Name', ['class' => 'control-label']) !!} -->
                            {!! Form::text('phone_no', old('phone_no'), ['class' => 'form-control', 'placeholder' => 'Phone Number', 'required' => true]) !!}
                        </div>

                        <div class="col-12 form-group text-center">

                            {!! Form::submit(('Create student number'), ['class' => 'btn mt-auto  btn-danger' ,'id' => 'submit']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
@endsection

@push('after-scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">


    $('#date').datepicker({
         todayHighlight: true,
         autoclose: true,
    });
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
    $('#fee_plan').select2({
    	placeholder: "Fee Plan",
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
