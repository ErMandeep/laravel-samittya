@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <style>
        .couse-pagination li.active {
            color: #333333!important;
            font-weight: 700;
        }
        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color:white;
            border:none;

        }
        ul.pagination{
            display: inline;
            text-align: center;
        }
        .buttons {
        display: none;
        padding-left: 15px;
}
    .feature {
    width: 200px;
    height: 200px;
    border-radius: 100%;
    background: white;
    border: 1px solid #fc1b67;
    box-shadow: 1px 1px 10px 1px #b1aeae;
}
.becometeacher-heading{
          text-align: center;
    text-transform: uppercase;
    color: #3d2d48;
        padding: 25px;
}
.becometeacher-heading h2{
      font-size: 33px;
    font-weight: 600;
}
.feature-icon {
    width: 160px;
    height: 137px;
    margin: 40px auto 0px;
}
.pandey{
      background-color: #ffffff;
    box-shadow: 0px 0px 7px 1px #a5a0a0;
    padding: 10px 20px;
}
.jumbotron {
    /*padding: 2rem 1rem;*/
        padding: 180px 1rem;
    margin-bottom: 0rem;
    background-color: #e9ecef;
    border-radius: .3rem;
}
.cutom-btn{
      background: linear-gradient(90deg, rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
          color: rgb(255, 255, 255) !important;
}

.display-3 {
    font-size: 3.5rem;
    font-weight: 300;
    line-height: 1.2;
}
span.fieldset-legend.js-form-required.form-required {
    text-transform: capitalize;
}
a.terms{
  color: #3d2d48 !important;
}

    </style>
@endpush
@section('content')

<!-- @if( auth()->check() &&  @$logged_in_user->hasRole('live teacher') &&  @$logged_in_user->hasRole('teacher'))

 <div class="jumbotron text-center">
  <h3 class="display-3">You Already Register With Teacher Profile</h3>

</div>


@elseif( auth()->check() &&  @$logged_in_user->hasRole('live teacher') )


 <div class="jumbotron text-center">
  <h1 class="display-3">Request For Regular Teacher</h1>
 
<br>
   {!! Form::open(['method' => 'POST', 'route' => ['becometeacher.regular_teacher_resquest'], 'files' => true,]) !!}

 {!! Form::submit('Become a Regular Teacher', ['class' => 'btn btn-lg cutom-btn']) !!}

  {!! Form::close() !!}

</div>



@elseif( auth()->check() &&  @$logged_in_user->hasRole('teacher') )




 <div class="jumbotron text-center">
  <h1 class="display-3">Request For Live Teacher</h1>
 
<br>

<a href="#"   data-toggle="modal" data-target="#isteacher" class="btn btn-lg cutom-btn">Become a Live Teacher</a>

</div>


@endif -->

@if( !auth()->check())
 <div class="jumbotron text-center">
  <!-- <h1 class="display-3">LOGIN OR SIGN UP</h1> -->
  <h1 class="display-3">Register Before Creating Your Profile</h1>
 
<br>

<a class=" go-register btn btn-lg cutom-btn" id="openLoginModal" data-target="#myModal" href="#">LOGIN OR SIGN UP</a>

</div>

@else

@if( auth()->check() &&  @$logged_in_user->hasRole('live teacher') ||  @$logged_in_user->hasRole('teacher'))

 <div class="jumbotron text-center">
  <h3 class="display-3">You Already Register With Teacher Profile</h3>

</div>



@endif




@if( auth()->check() && !$logged_in_user->hasRole('teacher') && !$logged_in_user->hasRole('live teacher'))

@if($user->regular == 'regular' || $user->live == 'live')

 <div class="jumbotron text-center">
  <h3 class="display-3">Your Request currently in Process</h3>

</div>

@else

<div role="main" style="background-color: #eee;padding-bottom: 40px;" class="main-container js-quickedit-main-content">
                                 <div class="highlighted">  <div class="region region-highlighted">
    

  </div>
</div>
                     <div class="container">
<div class="row "> 

<div class="col-md-12 becometeacher-heading">
  <h2>Become a Teacher </h2>
</div>

</div>

    <div class="row">

      <aside class="col-lg-6 col-md-6 col-sm-6 col-xs-12" role="complementary">
             
    
         <div class="row mt100">
          <div class="col-md-5">
            <div class="feature">
              <div class="feature-icon">
                <img src="{{asset('assets/images/undraw_teacher_35j2.svg')}}"></div>

              <!-- <h3>Access anytime <br>anywhere</h3> -->
            </div>
          </div>

          <div class="col-md-7" style="min-height: 210px;
    margin-top: 40px;">
            
              <h4>Sign Up</h4>
              <p>We request the teachers to create an account on Samitya by filling this Sign Up form.</p>
         
          </div>          
        </div>
  
         <div class="row mt100">
          <div class="col-md-5">
            <div class="feature">
              <div class="feature-icon">
                <img src="../assets/images/undraw_content_vbqo.svg"></div>

              <!-- <h3>Access anytime <br>anywhere</h3> -->
            </div>
          </div>

          <div class="col-md-7" style="min-height: 210px;
    margin-top: 40px;">
            
              <h4>Create Teacher Profile</h4>
              <p>After signing up, please fill in your profile details & submit it for screening & approval.</p>
         
          </div>            
        </div>

         <div class="row mt100">
          <div class="col-md-5">
            <div class="feature">
              <div class="feature-icon">
                <img src="../assets/images/undraw_press_play_bx2d.svg"></div>

              <!-- <h3>Access anytime <br>anywhere</h3> -->
            </div>
          </div>

          <div class="col-md-7" style="min-height: 210px; margin-top: 40px; ">
            
              
              <h4>Create & teach courses</h4>
              <p>After the teacher profile & bio is approved, the teacher will be able to create, publish and teach courses on samitya.</p>
         
 
          </div>            
        </div>        
          </aside>



              
                  <section class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pandey"  >


  <div class="signup-info">

            <div class="region region-content">
    {!! Form::open(['method' => 'POST', 'route' => ['becometeacher.saveSequence'], 'files' => true,]) !!}
  


       <legend>
    <span class="fieldset-legend js-form-required form-required">Phone Number</span>
  </legend>
<div class=" form-group">
  
                      {!! Form::number('phone', old('phone'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.becometeacher.fields.phone'), 'pattern' => "[0-9]"]) !!}

</div>

  
  


       <legend>
    <span class="fieldset-legend js-form-required form-required">Year of Birth</span>
  </legend>
<div class="form-group">

<div class="select-wrapper">
  <select data-drupal-selector="edit-entity-teacher-field-year-of-birth" class="form-select required form-control" id="edit-entity-teacher-field-year-of-birth" name="dob" required="required" aria-required="true">

    @for ($i = 1920; $i <= 2010; $i++)
        <option value="{{ $i }}">{{ $i }}</option>
    @endfor


  </select>
</div>

  
  
  
  </div>





      <legend>
    <span class="fieldset-legend js-form-required form-required">Gender</span>
  </legend>
  
<div class="radio radio-info">
    <input type="radio" name="gender" id="Radios2" value="male">
  <label>
    Male
  </label>
</div>


<div class="radio radio-info">
    <input type="radio" name="gender" id="Radios2" value="female">
  <label>
    Female
  </label>
</div>
  
<!-- **************** -->
      <legend>
    <span class="fieldset-legend js-form-required form-required">teacher Bio</span>
  </legend>
      <div class="form-group">
<textarea class="form-url form-control" name="description">   </textarea>

  </div>


      <legend>
    <span class="fieldset-legend js-form-required form-required">Profile Picture</span>
  </legend>
  <div class="form-group">
 {!! Form::file('image', ['class' => 'form-control d-inline-block', 'placeholder' => '']) !!}
</div>

      <legend>
    <span class="fieldset-legend js-form-required form-required">Select Teacher type</span>
  </legend>
 
<div class="row">
      <div class="form-group">
    <div class="col-md-12 inputGroupContainer">
<input type="checkbox" name="regular" value="regular"> Regular Teacher 
  </div>
</div>
</div>




<!-- <input name="featured_ad" value="1" type="checkbox" onclick="resetradio(this)">condition -->
<input class="form-group" name="live" value="live" type="checkbox" onclick="resetradio(this)"> Live Teacher 

<div class="buttons">
    <input class="form-group" type="radio" name="software" value="zoom" onclick="setcheckbox()"> Zoom (Recommended)<br>
    <input class="form-group" type="radio" name="software" value="skype" onclick="setcheckbox()"> Skype<br>
</div>


        

</div>
<!-- **************** -->







      <legend>
    <span class="fieldset-legend js-form-required form-required">Facebook profile</span>
  </legend>
      <div class="form-group">
     
  
  
  <input  class="form-url form-control" type="url" name="facebookurl" value="" size="60" maxlength="2048" placeholder="">

  </div>
      <legend>
    <span class="fieldset-legend js-form-required form-required">Instagram profile</span>
  </legend>
      <div class="form-group">
 
  

  <input  class="form-url form-control" type="url" name="instagramurl" value="" size="60" maxlength="2048" placeholder="">

  </div>

  <legend>
    <span class="fieldset-legend js-form-required form-required">Youtube profile</span>
  </legend>
      <div class="form-group">
 
  

  <input  class="form-url form-control" type="url" name="youtubeurl" value="" size="60" maxlength="2048" placeholder="">

  </div>

      <legend>
    <span class="fieldset-legend js-form-required form-required">Terms & Conditions</span>
  </legend>


      
            
    
    <div class="panel-body">
<div class="form-item js-form-item form-type-checkbox js-form-type-checkbox  checkbox">
  
  
  
<input type="checkbox" name="" required>
      <label for="" class="control-label option js-form-required form-required"> <a class="terms" target="_blank" href="{{ url('/terms-conditions') }}"> I accept Samitya Terms & Conditions</a></label>
  
  
  
  </div>

          </div>
  





<div data-drupal-selector="edit-actions" class="form-actions form-group js-form-wrapper form-wrapper" id="edit-actions">


 {!! Form::submit('Submit', ['class' => 'btn btn-lg cutom-btn']) !!}

</div>


  </div>

              </div>
      </section>

                </div>
   </div>
  </div>
@endif
@endif
@endif






 {!! Form::close() !!}

    <div class="modal fade" id="isteacher" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header backgroud-style">

                    <div class="gradient-bg"></div>
                    <div class="popup-logo">
                        <img src="<?php echo e(asset("storage/logos/".config('logo_popup'))); ?>" alt="">
                    </div>
                    <div class="popup-text text-center">
                        <h2>SELECT TEACHER TYPE  </h2>
                        <!-- <p><?php echo app('translator')->getFromJson('labels.frontend.modal.login_register'); ?></p> -->
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane container active" >

                            <span class="error-response text-danger"></span>
                            <span class="success-response text-success"></span>


     {!! Form::open(['method' => 'POST', 'route' => ['becometeacher.live_teacher_resquest'], 'files' => true,]) !!}
                                
                             
                                <div class="contact-info mb-2">
                                  <select class="form-control mb-0" name="software" id="software">
              <option value="zoom" data-sub_plan ="1"> Zoom</option>
              <option value="skype" data-sub_plan="2"> Skype</option>
                                    </select>
                                 
                                </div>
                              




                                <div class="nws-button text-center white text-capitalize">
                                    <button type="submit"
                                            value="Submit"> Proceed </button>

                                </div>
                             {!! Form::close() !!}
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {







            $(document).on('change','#sortBy',function () {
               if($(this).val() != ""){
                   location.href = '{{url()->current()}}?type='+$(this).val();
               }else{
                   location.href = '{{route('courses.all')}}';
               }
            })

            @if(request('type') != "")
                $('#sortBy').find('option[value="'+"{{request('type')}}"+'"]').attr('selected',true);
            @endif
        });



        function resetradio (checkbox) {
    var buttons = document.querySelector('.buttons');
    var radios = document.getElementsByName('software');
    radios[0].checked = true;
    if (checkbox.checked == true) {
        buttons.style.display = 'block';
    }
    else {
        buttons.style.display = 'none';
    }
}

function setcheckbox () {
    var checkbox = document.getElementsByName('live')[0];
    if (checkbox.checked == false) {
        checkbox.checked = true;
    }
}

    </script>


@endpush


<!-- ************************ -->





@push('after-scripts')
    <script>
  $(document).on('change','#amount',function(){
    var dataid = $('option:selected', this).data('sub_plan');
    $('#name').val(dataid);
});


    </script>
@endpush