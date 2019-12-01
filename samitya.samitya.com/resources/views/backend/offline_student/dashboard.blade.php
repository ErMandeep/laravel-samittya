@inject('request', 'Illuminate\Http\Request')

<!DOCTYPE html>
@if(config('app.display_type') == 'rtl' || (session()->has('display_type') && session('display_type') == 'rtl'))
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

    @else
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

        @endif
        {{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">--}}
        {{--@else--}}
        {{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
        {{--@endlangrtl--}}
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <title>@yield('title', app_name())</title>
            <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
            <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
            @if(config('favicon_image') != "")
                <link rel="shortcut icon" type="image/x-icon"
                      href="{{asset('storage/logos/'.config('favicon_image'))}}"/>
            @endif
            @yield('meta')
            <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/css/fontawesome-all.css')}}">

            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

            <link rel="stylesheet"
                  href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css"/>
            <link rel="stylesheet"
                  href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css"/>
            <link rel="stylesheet"
                  href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
            {{--<link rel="stylesheet"--}}
            {{--href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css"/>--}}
            {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
            <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/timegrid/main.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/list/main.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.css" rel="stylesheet">
            <link href="{{ asset('assets/css/backend/style.css') }}" rel="stylesheet">

            


            @stack('offline-before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
            <!-- Otherwise apply the normal LTR layouts -->
            {{ style(mix('css/backend.css')) }}


            @stack('offline-after-styles')

            @if((config('app.display_type') == 'rtl') || (session('display_type') == 'rtl'))
                <style>
                    .float-left {
                        float: right !important;
                    }

                    .float-right {
                        float: left !important;
                    }
                </style>
            @endif

        </head>

        <body class="{{ config('backend.body_classes') }}">

            <!-- Header --> 
            <header class="app-header navbar">
                 <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <img class="navbar-brand-full" src="{{asset('storage/logos/'.config('logo_b_image'))}}"  height="25" alt="Square Logo">
                    <img class="navbar-brand-minimized" src="{{asset('storage/logos/'.config('logo_popup'))}}" height="30" alt="Square Logo">
                </a>
                <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <ul class="nav navbar-nav d-md-down-none">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('frontend.index') }}"><i class="icon-home"></i></a>
                    </li>

                    <li class="nav-item px-3">
                        <a class="nav-link" href="{{ route('offline.dashboard') }}">@lang('navs.frontend.dashboard')</a>
                    </li>
                </ul>

                <ul class="nav navbar-nav ml-auto mr-4">

                    <li class="nav-item dropdown">
                      <a class="nav-link"  href="{{ route('offline.logout') }}" role="button" aria-haspopup="true" aria-expanded="false">

                          <span style="right: 0;left: inherit" class="badge d-md-none d-lg-none d-none mob-notification badge-success">!</span>
                        <span class="d-md-down-none">@lang('navs.general.logout')</span>
                      </a>
                      
                    </li>
                </ul>
            </header>
            <!-- /Header --> 


        <div class="app-body">
        <div id="loader" style="background-image: url('{{ asset('assets/images/preloader.gif') }}'); display: none; "></div>
            <!-- Sidebar  -->
            <div class="sidebar">
                <nav class="sidebar-nav">
                    <ul class="nav">
                        <li class="nav-title">
                            @lang('menus.backend.sidebar.general')
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ active_class(Active::checkUriPattern('offline/dashboard')) }}"
                               href="{{ route('offline.dashboard') }}">
                                <i class="nav-icon icon-speedometer"></i> @lang('menus.backend.sidebar.dashboard')
                            </a>
                        </li>
                    </ul>
                </nav>

                <button class="sidebar-minimizer brand-minimizer" type="button"></button>
            </div>
            <!-- /Sidebar  -->
          

            <main class="main">
                <div class="container-fluid" style="padding-top: 30px">
                    <div class="animated fadeIn">
                        <div class="content-header">
                            @yield('offline-page-header')
                        </div><!--content-header-->
                        @yield('offline-content')
                    </div><!--animated-->
                </div><!--container-fluid-->
            </main><!--main-->

     
        </div><!--app-body-->

        @include('backend.includes.footer')

        <!-- Scripts -->
        @stack('offline-before-scripts')
        {!! script(mix('js/manifest.js')) !!}
        {!! script(mix('js/vendor.js')) !!}
        {!! script(mix('js/backend.js')) !!}
        <script>
            //Route for message notification
            var messageNotificationRoute = '{{route('admin.messages.unread')}}'
        </script>
        <script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script src="{{asset('js/pdfmake.min.js')}}"></script>
        <script src="{{asset('js/vfs_fonts.js')}}"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


        <script src="{{asset('assets/js/backend/backend.js')}}"></script>


        <script>
            window._token = '{{ csrf_token() }}';
        </script>

        @stack('offline-after-scripts')

        </body>
        </html>
