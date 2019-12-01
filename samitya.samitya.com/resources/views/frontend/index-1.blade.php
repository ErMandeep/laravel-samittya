@extends('frontend.layouts.app'.config('theme_layout'))



@section('title', trans('labels.frontend.home.title').' | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')

<?php
// Route::getCurrentRoute()->getAction();
// Route::currentRouteAction();
// print_r(Route::currentRouteAction() );

//   die; 
  ?>
@push('after-styles')
    <style>
        /*.address-details.ul-li-block{*/
        /*line-height: 60px;*/
        /*}*/
        .teacher-img-content .teacher-social-name{
            max-width: 67px;
        }
        .my-alert{
            position: absolute;
            z-index: 10;
            left: 0;
            right: 0;
            top: 25%;
            width: 50%;
            margin: auto;
            display: inline-block;
        }
    </style>
@endpush

@section('content')

    <!-- Start of slider section
            ============================================= -->
    @if(session()->has('alert'))
    <div class="alert alert-light alert-dismissible fade my-alert show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{session('alert')}}</strong>
    </div>
    @endif
    
  @include('frontend.layouts.partials.banner')
      <section class="offer-section">
    
      <div class="container">
        <div class="row mt100">
          <div class="col-md-4">
            <div class="feature">
              <div class="feature-icon">
                <img src="../assets/images/access_anytime.svg"></div>
              <h3>Access anytime <br>anywhere</h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature">
              <div class="feature-icon chat-icon"><img src=" ../assets/images/chat_icons.svg"></div>
              <h3>Chat with teachers for <br>feedback</h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature">
               <div class="feature-icon"><img src="../assets/images/live_classes.svg"></div>
              <h3>Live classes<br> Coming soon</h3>
            </div>
          </div>
        </div>
      </div>
      
    </section>



    @if($sections->search_section->status == 15)

        <!-- End of slider section
            ============================================= -->
        <section id="search-course" class="search-course-section">
            <div class="container">
                <div class="section-title mb20 headline text-center ">
                    <span class="subtitle text-uppercase">@lang('labels.frontend.home.learn_new_skills')</span>
                    <h2>@lang('labels.frontend.home.search_courses')</h2>
                </div>
                <div class="search-course mb30 relative-position ">
                    <form action="{{route('search')}}" method="get">
                        <input class="course" name="q" type="text"
                               placeholder="@lang('labels.frontend.home.search_course_placeholder')">
                        <div class="nws-button text-center  gradient-bg text-capitalize">
                            <button type="submit" value="Submit">@lang('labels.frontend.home.search_course')</button>
                        </div>
                    </form>
                </div>
                <div class="search-counter-up">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="counter-icon-number ">
                                <div class="counter-icon">
                                    <i class="text-gradiant flaticon-graduation-hat"></i>
                                </div>
                                <div class="counter-number">
                                    <span class=" bold-font">{{$total_students}}</span>
                                    <p>@lang('labels.frontend.home.students_enrolled')</p>
                                </div>
                            </div>
                        </div>
                        <!-- /counter -->

                        <div class="col-md-4 col-sm-4">
                            <div class="counter-icon-number ">
                                <div class="counter-icon">
                                    <i class="text-gradiant flaticon-book"></i>
                                </div>
                                <div class="counter-number">
                                    <span class=" bold-font">{{$total_courses}}</span>
                                    <p>@lang('labels.frontend.home.online_available_courses')</p>
                                </div>
                            </div>
                        </div>
                        <!-- /counter -->


                        <div class="col-md-4 col-sm-4">
                            <div class="counter-icon-number ">
                                <div class="counter-icon">
                                    <i class="text-gradiant flaticon-group"></i>
                                </div>
                                <div class="counter-number">
                                    <span class=" bold-font">{{$total_teachers}}</span>
                                    <p>@lang('labels.frontend.home.teachers')</p>
                                </div>
                            </div>
                        </div>
                        <!-- /counter -->
                    </div>
                </div>
            </div>
        </section>
        <!-- End of Search Courses
            ============================================= -->
    @endif


    
    @include('frontend.layouts.partials.popular_courses')
    

   
    <!-- Start Course category
    ============================================= -->
    @include('frontend.layouts.partials.course_by_tags')
    <!-- End Course category
        ============================================= -->


   
    <!-- Start Live classes Section
    ============================================= -->
    @include('frontend.layouts.partials.live_classes')
    <!-- End Live classes Section
        ============================================= -->
    

   
    <!-- Start Teacher Slider
    ============================================= -->
    @include('frontend.layouts.partials.teacher_slider')
    <!-- End Teacher Slider
        ============================================= -->
    







@endsection

@push('after-scripts')
    <script>
        $('ul.product-tab').find('li:first').addClass('active');
    </script>
@endpush
