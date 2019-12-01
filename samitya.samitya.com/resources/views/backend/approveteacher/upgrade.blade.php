@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection
@push('after-styles')
    <style>
        .modal-content .modal-header {
    padding: 0;
}
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
        /*padding: 180px 1rem;*/
    margin-bottom: 0rem;
    background-color: #e9ecef;
    border-radius: .3rem;
}
.cutom-btn{
      background: linear-gradient(90deg, rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
          color: rgb(255, 255, 255) !important;
}

.gradient-bg, .modal-body .nws-button button, .teacher-pic-content .teacher-img-content:after, .course-details-category li:hover {
    background: linear-gradient(90deg ,rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
    /* background: #17d0cf; */
    /* background-size: 200% auto; */
    -webkit-transition: background 1s ease-out;
    transition: background 1s ease-out;
}
.modal-body .nws-button button {
    height: 60px;
    width: 100%;
    border: none;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    font-size: 18px;
    border-radius: 40px;
}
[type=reset], [type=submit], button, html [type=button] {
    -webkit-appearance: button;
}
.modal-content .modal-header {
    padding: 0;
}
.modal-header {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: .3rem;
    border-top-right-radius: .3rem;
}

.backgroud-style {
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
}
.modal-header {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: .3rem;
    border-top-right-radius: .3rem;
}

    </style>
@endpush
@section('content')


<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                   
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
              
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
 

 @if( auth()->check() &&  @$logged_in_user->hasRole('live teacher') &&  @$logged_in_user->hasRole('teacher'))

 <div class="jumbotron text-center">
  <h3 class="display-3">You Already Register With Teacher Profile</h3>

</div>


@elseif( auth()->check() &&  @$logged_in_user->hasRole('live teacher') )


 <div class="jumbotron text-center">
  <h1 class="display-3">Request Regular Teaching</h1>
 
<br>
   {!! Form::open(['method' => 'POST', 'route' => ['admin.approveteacher.regular_teacher_resquest'], 'files' => true,]) !!}

 {!! Form::submit('Become a Regular Teacher', ['class' => 'btn btn-lg cutom-btn']) !!}

  {!! Form::close() !!}

</div>



@elseif( auth()->check() &&  @$logged_in_user->hasRole('teacher') )



 <div class="jumbotron text-center">
  <h1 class="display-3">Request Live Teaching</h1>
 
<br>



<a href="#"   data-toggle="modal" data-target="#isteacher" class="btn btn-lg cutom-btn">Become a Live Teacher</a>

</div>


@endif
                </div>
            </div><!--col-->
        </div><!--row-->

    </div><!--card-body-->
</div><!--card-->
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
                        <h3>SELECT SOFTWARE TYPE  </h3>
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


     {!! Form::open(['method' => 'POST', 'route' => ['admin.approveteacher.live_teacher_resquest'], 'files' => true,]) !!}
                                
                             
                                <div class="contact-info mb-2">
                                  <select class="form-control mb-0" name="software" id="software">
              <option value="zoom" data-sub_plan ="1">Zoom (Recommended)</option>
              <option value="skype" data-sub_plan="2">Skype</option>
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



@push('after-scripts')
    <script>
  $(document).on('change','#amount',function(){
    var dataid = $('option:selected', this).data('sub_plan');
    $('#name').val(dataid);
});


    </script>
@endpush