<!DOCTYPE html>
@langrtl
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @endlangrtl
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @push('after-styles')
<style>

 .nws-button a:hover {
    color: #fff;
}
 .nws-button a{
    color: #fff;
}
  .proceedd{
    border: none;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    font-size: 18px;
    border-radius: 40px;
    background: linear-gradient(90deg ,rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
    padding: 14px 30px;
  }
.gift3 {
    position: absolute !important;
    z-index: 999;
    left: -60px;
    top: 9px;
    opacity: 0;
    visibility: hidden;
    transition: ease-out .35s;
    -moz-transition: ease-out .35s;
    -webkit-transition: ease-out .35s;
}

.gift2 {
    margin: 0 auto;
    position: relative;
}
.mainnav li:hover {
    background-color: #3d2d48;
    color: #fff !important;
}
.popup-new h5{
  /*color: #fff;*/
  margin-top: 10px;
  text-transform: uppercase;
  font-size: 17px;
}
.popup-new h4{
  color: #fff;
  margin-top: 10px;
  text-transform: uppercase;
}
.popup-new {
    width: 100%;
    padding: 13px 0px 10px 0px;
    position: relative;
}
.popup-img{
  width: 38%;
  border-radius: 8px;
}
.modal-header .close {
    padding: 1rem;
    margin: -1rem 0rem 0rem auto;
}
.newslettr{
  width: 80px;
}
.modal-body .nws-button-news button {
    height: 40px;
    width: 35%;
    border: none;
    background: linear-gradient(90deg ,rgba(251, 95, 47, 0.9), rgba(253, 21, 100, 0.9));
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    font-size: 18px;
    border-radius: 40px;
}
            </style>
@endpush
        @if(config('favicon_image') != "")
            <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/logos/'.config('favicon_image'))}}"/>
        @endif
        
        <title>@yield('title', app_name())</title>


        <meta name="description" content="@yield('meta_description', '')">
        <meta name="keywords" content="@yield('meta_keywords', '')">

        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->

        <link rel="stylesheet" href="{{asset('assets/css/categorymenu.css')}}">

        <!-- <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.css')}}"> -->
        <link rel="stylesheet" href="{{asset('assets/css/flaticon.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/meanmenu.css')}}">
        <!-- <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}"> -->
        <link rel="stylesheet" href="{{asset('assets/css/video.min.css')}}">
        <!-- <link rel="stylesheet" href="{{asset('assets/css/lightbox.css')}}"> -->
        <link rel="stylesheet" href="{{asset('assets/css/progess.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
        {{--<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">--}}
        <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

        <link rel="stylesheet" href="{{asset('assets/css/fontawesome-all.css')}}">

        <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
        <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/colors/switch.css')}}">
        <!-- 
        <link href="{{asset('assets/css/colors/color-2.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-2">
        <link href="{{asset('assets/css/colors/color-3.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-3">
        <link href="{{asset('assets/css/colors/color-4.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-4">
        <link href="{{asset('assets/css/colors/color-5.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-5">
        <link href="{{asset('assets/css/colors/color-6.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-6">
        <link href="{{asset('assets/css/colors/color-7.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-7">
        <link href="{{asset('assets/css/colors/color-8.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-8">
        <link href="{{asset('assets/css/colors/color-9.css')}}" rel="alternate stylesheet" type="text/css"
              title="color-9">
 -->

        <!-- <link rel="stylesheet" href="{{asset('assets/css/colors/bootstrap.min.css')}}"> -->
        <link rel="stylesheet" href="{{asset('assets/css/colors/style.css')}}?v=<?php echo rand(); ?>">
        <!-- <link href="{{asset('assets/fonts/Montserrat-SemiBold.otf')}}">
        <link href="{{asset('assets/fonts/Montserrat-Regular.otf')}}  ">
        <link href="{{asset('assets/fonts/Montserrat-Bold.otf')}}  ">
        <link href="{{asset('assets/fonts/Montserrat-ExtraBold.otf')}}  ">
        <link href="{{asset('assets/fonts/Montserrat-Black.otf')}}  "> -->
        <link href="{{asset('assets/fonts/VarelaRound-Regular.otf')}}  ">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous"> -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
        <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/slick/slick.css')}}   "/> -->
        <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/slick/slick-theme.css')}}   "/> -->
        <link rel="stylesheet" href="{{asset('assets/css/3d-style.css')}}   ">
        <link rel="stylesheet" href="{{asset('assets/css/course.css')}}?v=<?php echo rand(); ?>">
        <link rel="stylesheet" href="{{asset('assets/css/colors/responsive.css')}}?v=<?php echo rand(); ?>">
        <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jquery-steps@1.1.0/demo/css/jquery.steps.css"> -->
        <link rel="stylesheet" href="{{asset('assets/css/showYtVideo.css')}}?v=<?php echo rand(); ?>">

    
<!-- <link href="https://vjs.zencdn.net/7.5.5/video-js.css" rel="stylesheet"> -->
<!-- <link href="https://vjs.zencdn.net/7.0/video-js.min.css" rel="stylesheet"> -->
        <link rel="stylesheet" href="{{asset('assets/css/zencdn.css')}}   ">
        <link rel="stylesheet" href="{{asset('assets/css/jsdelivr.css')}}   ">

  <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->

<!-- <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet"> -->
        <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.min.css')}}   ">




    <script  src="{{asset('assets/js/jquery-2.1.4.min.js')}}" ></script>
<!-- <script src='https://vjs.zencdn.net/7.5.5/video.js' defer></script> -->
    <script  src="{{asset('assets/js/videojs.js')}}" defer ></script>


  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-youtube/2.6.0/Youtube.js" defer></script> -->
    <script src="{{asset('assets/js/youtubejs.js')}}" defer ></script>
 <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/videojs-vimeo@2.0.2/src/Vimeo.min.js"></script> -->
    <script src="{{asset('assets/js/Vimeo.min.js')}}" defer></script>
<!-- ShareThis BEGIN -->



<!-- ShareThis END -->




        @yield('css')
        @stack('after-styles')

        @if(config('google_analytics_id') != "")
    <!-- Global site tag (gtag.js) - Google Analytics -->
<!-- 
        <script async src="https://www.googletagmanager.com/gtag/js?id={{config('google_analytics_id')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '{{config('google_analytics_id')}}');
        </script> -->


        <!-- Google Analytics -->
          <script>
          window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
          ga('create', '{{config('google_analytics_id')}}', 'auto');
          ga('send', 'pageview');
          </script>
          <script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->


            @endif


    </head>
    <body class="{{config('layout_type')}}">
    @include('frontend.layouts.modals.loginModal')



  
    <div id="app">
    {{--<div id="preloader"></div>--}}


    <!-- Start of Header section
        ============================================= -->
        <header  class="headerWrapper">
              <nav class="navbar navbar-expand-lg samitya-header">
        <div class="container">
          <div class="samitya-header-inner">
        <div class="samitya-logo col-md-3 col-lg-3"><a class="navbar-brand" href="/"><img src="{{asset("storage/logos/".config('logo_w_image'))}}"></a></div>
            <div class="samitya-menu col-md-7 col-lg-7">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
              </button>


         


              <div class="collapse navbar-collapse " id="navbarsExampleDefault">




                <ul class="navbar-nav ml-auto">
<li class="menu-item-has-children ul-li-block nav-item hassubs">
                        <a href="#" class="nav-link category_item" >Categories</a>
                    <ul class="mainnav">
                        <ul class="dropdown">
                        @if(@$cat_menu)
                            @foreach($cat_menu->take(10) as $menu)
@php                         
if (strpos($_SERVER['REQUEST_URI'], "live-classes") !== false){
$path = 'liveclass.category';
}
elseif (strpos($_SERVER['REQUEST_URI'], "live-courses") !== false){
$path = 'liveclass.category';

}else{
$path = 'courses.category';
} @endphp

                            <li class="subs hassubs ul-li-block">
                                <a href="{{ route($path, [$menu->slug] )   }}"><i style="padding: 0px 10px;" class="{{ $menu->icon }}"></i>{{$menu->name}}</a>
                                <ul class="dropdown">
                            @foreach($sub as $subitem)
                            @if($menu->id  == $subitem->parent_id)
                            
                                <li class="subs"><a href="{{ route($path, [$subitem->slug] )   }}">{{$subitem->name}}</a></li>
                           
                            @endif
                            @endforeach
                             </ul>
                           
                            </li>
                            @endforeach
                            @endif

                        </ul>
                    </ul>
                    </li>


                    <!-- ************************** -->
                           <li class="nav-item ">
                                        <a href=" {{url('/becometeacher')}}"
                                           class="nav-link"
                                           id="">Become an Instructor</a>
                                    </li> 

                      <!-- ****************************-->

                      
                                <li class="nav-item gift-icon live-icon hassubs">
                                <a href="#"
                                   class="nav-link"
                                   id="">Gift</a>
                                   <ul class="mainnav gift2">
                                       <ul class="dropdown gift3" >  
                                       <!-- <li class="subs hassubs" ><a href="{{route('courses.allshow')}}">Regular Courses</a></li> -->
                                       <li class="subs hassubs" ><a href="#" data-toggle="modal" data-target="#welcome" >Regular Courses</a></li>
                                       <li class="subs hassubs" ><a href="#" data-toggle="modal" data-target="#livewelcome" >Live Courses</a></li>
                                       <!-- <li class="subs hassubs"><a href="{{route('liveclass.alllive')}}">Live Courses</a></li> -->
                                       </ul>  
                                       </ul>    
                                </li> 
                              
                                     <li class="nav-item live-icon">
                                        <a href="{{route('liveclass.all')}}"
                                           class="nav-link"
                                           id="">Live Class</a>
                                    </li>  

                    <li class="nav-item">
                        <div class="login-signup-button log-sign-responsive">
                            <div class="login-signup">
                                
                                            @if(auth()->check())
                                       
                                                <!-- <a href="#!"> {{ $logged_in_user->name }} </a> -->
                                              
                                                    @can('view backend')
                                                
                                                  <!-- <div class="login-link ">    -->
                                                   <a class="login go-login"  href="{{ route('admin.dashboard') }}">Dashboard</a>
                                                    <!-- </div> -->
                                            
                                                    @endcan

                                                  
                                                     <!-- <div class="login-signup ">   -->
                                                      <a class="signup go-login"  href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
                                                  <!-- </div> -->
                                              @elseif(auth('offline')->check())
                                                <a class="login go-login"  href="{{ route('offline.dashboard') }}">Dashboard</a>
                                                <a class="signup go-login"  href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
                          
                                             
                                           
                                        @else

                                      <!-- <div class="login-link "> -->
                                        <a class="login go-register " id="openLoginModal" data-target="#myModal"
                                                       href="#">Sign up</a>
                                                   <!-- </div> -->


                                                <!-- <div class="login-signup "> -->
                                                    <a class="signup  go-login"   id="openLoginModal" data-target="#myModal"
                                                       href="#">@lang('navs.general.login')</a>
                                                    <!-- The Modal -->
                                                <!-- </div> -->
                                         
                                        @endif
                                </div>
                            </div>
                    </li>
                  
                </ul>
              </div>
            </div>



            <div class="login-signup-button dektop col-md-3">

              

<div class="">
                                        @if(auth()->check())
                                       
                                              
                                                    @can('view backend')
                                                
                                                          <div class="login-link ">    <a class="login go-login"  href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                                                    
                                                    @endcan

                                                  
                                                     <div class="login-signup ">   <a class="signup go-login"  href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a></div>

                                                @elseif(auth('offline')->check())
                                                  <div class="login-link ">    <a class="login go-login"  href="{{ route('offline.dashboard') }}">Dashboard</a></div>

                                                  <div class="login-signup ">   <a class="signup go-login"  href="{{ route('offline.logout') }}">@lang('navs.general.logout')</a></div>
                                              
                                             
                                           
                                        @else

                                      <div class="login-link "><a class="login go-register " id="openLoginModal" data-target="#myModal"
                                                       href="#">Sign up</a></div>


                                                <div class="login-signup ">
                                                    <a class="signup  go-login"   id="openLoginModal" data-target="#myModal"
                                                       href="#">@lang('navs.general.login')</a>
                                                </div>
                                         
                                        @endif
</div>


             
            
            </div>
          </div>
        </div>
      </nav>
 
                       
                            <div class="mobile-menu">
                                <div class="logo">
                                    <a href="{{url('/')}}">
                                        <img src={{asset("storage/logos/".config('logo_w_image'))}} alt="Logo">
                                    </a>
                                </div>
                                <nav>
                                    <ul>
                                        @if(count($custom_menus) > 0 )
                                            @foreach($custom_menus as $menu)
                                                @if($menu['id'] == $menu['parent'])
                                                    @if(count($menu->subs) > 0)
                                                        <li class="">
                                                            <a href="#!">{{$menu->label}}</a>
                                                            <ul class="">
                                                                @foreach($menu->subs as $item)
                                                                    @include('frontend.layouts.partials.dropdown', $item)
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endif
                                                @else
                                                    <li class="">
                                                        <a href="{{asset($menu->link)}}"
                                                           class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"
                                                           id="menu-{{$menu->id}}">{{ $menu->label }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(auth()->check())
                                            <li class="">
                                                <a href="#!">{{ $logged_in_user->name}}</a>
                                                <ul class="">
                                                    @can('view backend')
                                                        <li>
                                                            <a href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a>
                                                        </li>
                                                    @endcan


                                                    <li>
                                                        <a href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @else
                                            <li>
                                                <div class=" ">
                                                    <a id="openLoginModal" data-target="#myModal"
                                                       href="#">@lang('navs.general.login')</a>
                                                    <!-- The Modal -->
                                                </div>
                                            </li>
                                        @endif
                                            @if(count($locales) > 1)
                                                <li class="menu-item-has-children ul-li-block">
                                                    <a href="#">
                                                    <span class="d-md-down-none">@lang('menus.language-picker.language')
                                                        ({{ strtoupper(app()->getLocale()) }})</span>
                                                    </a>
                                                    <ul class="">
                                                        @foreach($locales as $lang)
                                                            @if($lang != app()->getLocale())
                                                                <li>
                                                                    <a href="{{ '/lang/'.$lang }}"
                                                                       class=""> @lang('menus.language-picker.langs.'.$lang)</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                    </ul>
                                </nav>
 
        </header>
        <!-- Start of Header section
            ============================================= -->


         
 

        @yield('content')

        @include('frontend.layouts.partials.footer')

    </div><!-- #app -->

    <!-- Scripts -->
    @stack('before-scripts')

    <!-- For Js Library -->

    <script src="{{asset('assets/js/popper.min.js')}}" defer></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}" defer></script>
    <!-- <script src="{{asset('assets/js/owl.carousel.min.js')}}" defer></script> -->
    <script src="{{asset('assets/js/jarallax.js')}}" defer></script>
    <script src="{{asset('assets/js/jquery.magnific-popup.min.js')}}" defer></script>
    <!-- <script src="{{asset('assets/js/lightbox.js')}}" defer></script> -->
    <!-- <script src="{{asset('assets/js/jquery.meanmenu.js')}}" defer></script> -->
    <script src="{{asset('assets/js/scrollreveal.min.js')}}" defer></script>
    <!-- <script src="{{asset('assets/js/jquery.counterup.min.js')}}" defer></script> -->
    <!-- <script src="{{asset('assets/js/waypoints.min.js')}}" defer></script> -->
    <script src="{{asset('assets/js/jquery-ui.js')}}" defer></script>
    <!-- <script src="{{asset('assets/js/gmap3.min.js')}}" defer></script> -->

    <script src="{{asset('assets/js/switch.js')}}"></script>
    <script src="{{asset('assets/js/js.cookie.js')}}" ></script>

    
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.0/js.cookie.js"></script> -->


 <script src="{{asset('assets/js/jquery.showYtVideo.js?v='.rand())}}" ></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js" ></script>
 <script src="{{asset('js/select2.full.min.js')}}"  type="text/javascript"></script>

 
 <script src="{{asset('assets/js/custom.js?v='.rand())}}" ></script>


    <!-- <script src="{{asset('assets/js/script.js')}}" ></script> -->
    <script>


        @if((session()->has('show_login')) && (session('show_login') == true))
        $('#myModal').modal('show');
                @endif
        var font_color = "{{config('font_color')}}"



        // setActiveStyleSheet(font_color);
    </script>


    @yield('js')

    @stack('after-scripts')

    @include('includes.partials.ga')
    </body>
    </html>

<!-- ************************************************  tour for regular**************************************************************** -->
    <div class="modal fade" id="welcome" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header backgroud-style">

                   
                    <div class="popup-new text-center">

                        <h5>1. Select the course </h5>
                        <img src="{{asset('assets/images/step1.svg')}}" class="popup-img">
                        <h5>2. Click the gift icon </h5>
                        <img src="{{asset('assets/images/step2.svg')}}" class="popup-img">

                        <h5>3. Enter recepient email </h5>
                        <img src="{{asset('assets/images/step3.svg')}}" class="popup-img">
                        <h5>4. Complete the Payment </h5>
                        <img src="{{asset('assets/images/step4.svg')}}" class="popup-img">
                      
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane container active" >

                            <span class="error-response text-danger"></span>
                            <span class="success-response text-success"></span>

                             
                                <div class="contact-info mb-2">
                                
                                 
                                </div>
                              




                                <div class="nws-button text-center white text-capitalize">
                                                           <a href="{{route('courses.allshow')}}" class="proceedd"> Proceed</a>
                                </div>
                      
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- ************************************************  tour for live course **************************************************************** -->
    <div class="modal fade" id="livewelcome" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header backgroud-style">

                   
                    <div class="popup-new text-center">

                        <h5>1. Select the course </h5>
                        <img src="{{asset('assets/images/step1.svg')}}" class="popup-img">
                        <h5>2. Click the gift icon </h5>
                        <img src="{{asset('assets/images/step2.svg')}}" class="popup-img">

                        <h5>3. Enter recepient email </h5>
                        <img src="{{asset('assets/images/step3.svg')}}" class="popup-img">
                        <h5>4. Complete the Payment </h5>
                        <img src="{{asset('assets/images/step4.svg')}}" class="popup-img">
                      
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane container active" >

                            <span class="error-response text-danger"></span>
                            <span class="success-response text-success"></span>
                             
                                <div class="contact-info mb-2">
                                
                                 
                                </div>
                              
                                <div class="nws-button text-center white text-capitalize">
                        <a href="{{route('liveclass.alllive')}}" class="proceedd"> Proceed</a>
                                </div>
                      
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- ******************************************First tym show subscribe popup********************************** -->
<!-- 
<div id="onetym" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">


                <div class="modal-header backgroud-style">

                    <div class="gradient-bg"></div>
                    <div class="popup-logo">
                    </div>
                    <div class="popup-new text-center">
                        <img src="{{asset('assets/images/newsletter.png')}}" class="newslettr">
                      <h4>Subscribe To Our Newsletter</h4>

                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>
              <div class="modal-body">
                <div class="tab-content">
                    <div class="tab-pane container active" id="login">

                        <span class="error-response text-danger"></span>
                        <span class="success-response text-success"></span>
                        
              <form class="contact_form" action="{{route('subscribe')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="contact-info mb-2">
                  <input class="form-control mb-0" type="email" name="subs_email" placeholder="E-mail Address" maxlength="191">
                  <span id="login-email-error" class="text-danger"></span>
                  </div>
                  <div class="nws-button-news text-center white text-capitalize">
                  <button type="submit" value="Submit">Subscribe</button>
                  </div>
              </form>

                    </div>

                </div>
              </div>
        </div>
    </div>
</div> -->

<!-- ************************* -->
  