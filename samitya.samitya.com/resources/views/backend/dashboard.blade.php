@extends('backend.layouts.app')

@section('title', __('strings.backend.dashboard.title').' | '.app_name())

@push('after-styles')
    <style>
        .trend-badge-2 {
            top: -10px;
            left: -52px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            padding: 40px 40px 12px;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            background-color: #ff5a00;
        }

        .progress {
            background-color: #b6b9bb;
            height: 2em;
            font-weight: bold;
            font-size: 0.8rem;
            text-align: center;
        }

        .best-course-pic {
            background-color: #333333;
            background-position: center;
            background-size: cover;
            height: 150px;
            width: 100%;
            background-repeat: no-repeat;
        }
        .sub_message 
        {
        	padding: 10px;
		    margin-bottom: 10px;
		    background: #fb602d80;
		    color: #fff !important;
		    font-weight: 700;
        }
        .sub_message:hover{
        	background: #fb602d !important;

        }
        .sub_message a 
        {  
        	color: #fff; 
        }
        a:hover
        {
        	text-decoration: none;
        } 
        .sub_message .dismiss_msg
        {
        	float: right;
        	cursor: pointer;
        }


    </style>
@endpush

@section('content')


@if($expire_courses )
	@foreach($expire_courses as $excourse)
		
			<div class="sub_message" > 
			 <a href="{{ route('courses.show', [$excourse->slug]) }}">
				Your Subscribtion For {{ $excourse->title }} has expire. Click to Subscribe Again
			 </a>
				<i class="fas fa-times dismiss_msg"></i>
			</div>
	@endforeach
@endif

@if($review_course_name )
    @foreach($review_course_name as $excourse)
        
            <div class="sub_message" > 
             <a href="{{ route('courses.show', [$excourse->slug]) }}">
                Please Reviews this course {{ $excourse->title }}. Click to Reviews 
             </a>
                <i class="fas fa-times dismiss_msg"></i>
            </div>
    @endforeach
@endif

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <strong>@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->name }}!</strong>
                </div><!--card-header-->
                <div class="card-body">
                    <div class="row">
                        @if(auth()->user()->hasRole('student'))
                            <div class="col-12">
                                <h4>@lang('labels.backend.dashboard.my_courses')</h4>
                            </div>
                            @if(count($purchased_courses) > 0)
                                @foreach($purchased_courses as $item)

                                    <div class="col-md-3">
                                        <div class="best-course-pic-text position-relative border">
                                            <div class="best-course-pic position-relative overflow-hidden"
                                                 @if($item->course_image != "") style="background-image: url({{asset('storage/uploads/'.$item->course_image)}})" @endif>

                                                @if($item->trending == 1)
                                                    <div class="trend-badge-2 text-center text-uppercase">
                                                        <i class="fas fa-bolt"></i>
                                                        <span>@lang('labels.backend.dashboard.trending') </span>
                                                    </div>
                                                @endif

                                                <div class="course-rate ul-li">
                                                    <ul>
                                                        @for($i=1; $i<=(int)$item->rating; $i++)
                                                            <li><i class="fas fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="best-course-text d-inline-block w-100 p-2">
                                                <div class="course-title mb20 headline relative-position">
                                                    <h5>
                                                        <a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a>
                                                    </h5>
                                                </div>
                                                <div class="course-meta d-inline-block w-100 ">
                                                    <div class="d-inline-block w-100 0 mt-2">
                                                     <span class="course-category float-left">
                                                <a href="{{route('courses.category',['category'=>$item->category->slug])}}"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a>
                                            </span>
                                                        <span class="course-author float-right">
                                                 {{ $item->students()->count() }}
                                                            @lang('labels.backend.dashboard.students')
                                            </span>
                                                    </div>

                                                    <div class="progress my-2">
                                                        <div class="progress-bar"
                                                             style="width:{{$item->progress() }}%">{{ $item->progress()  }}
                                                            %
                                                            @lang('labels.backend.dashboard.completed')
                                                        </div>
                                                    </div>
                                                    @if($item->progress() == 100)
                                                        @if(!$item->isUserCertified())
                                                            <form method="post"
                                                                  action="{{route('admin.certificates.generate')}}">
                                                                @csrf
                                                                <input type="hidden" value="{{$item->id}}"
                                                                       name="course_id">
                                                                <button class="btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold"
                                                                        id="finish">@lang('labels.frontend.course.finish_course')</button>
                                                            </form>
                                                        @else
                                                            <div class="alert alert-success px-1 text-center mb-0">
                                                                @lang('labels.frontend.course.certified')
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center">
                                    <h4 class="text-center">@lang('labels.backend.dashboard.no_data')</h4>
                                    <a class="btn btn-primary"
                                       href="{{route('courses.all')}}">@lang('labels.backend.dashboard.buy_course_now')
                                        <i class="fa fa-arrow-right"></i></a>
                                </div>
                            @endif

                            @if(count($purchased_live_courses) > 0)
                                <div class="col-12">
                                    <br>
                                    <br>
                                    <h4>My Live Classes </h4>
                                </div>
                                @foreach($purchased_live_courses as $item)

                                    <div class="col-md-3">
                                        <div class="best-course-pic-text position-relative border">
                                            <div class="best-course-pic position-relative overflow-hidden"
                                                 @if($item->course_image != "") style="background-image: url({{asset('storage/uploads/'.$item->course_image)}})" @endif>

                                                @if($item->trending == 1)
                                                    <div class="trend-badge-2 text-center text-uppercase">
                                                        <i class="fas fa-bolt"></i>
                                                        <span>@lang('labels.backend.dashboard.trending') </span>
                                                    </div>
                                                @endif

                                                <div class="course-rate ul-li">
                                                    <ul>
                                                        @for($i=1; $i<=(int)$item->rating; $i++)
                                                            <li><i class="fas fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="best-course-text d-inline-block w-100 p-2">
                                                <div class="course-title mb20 headline relative-position">
                                                    <h5>
                                                        <a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a>
                                                    </h5>
                                                </div>
                                                <div class="course-meta d-inline-block w-100 ">
                                                    <div class="d-inline-block w-100 0 mt-2">
                                                     <span class="course-category float-left">
                                                <a href="{{route('courses.category',['category'=>$item->category->slug])}}"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a>
                                            </span>
                                                        <span class="course-author float-right">
                                                 {{ $item->students()->count() }}
                                                            @lang('labels.backend.dashboard.students')
                                            </span>
                                                    </div>

                                                   
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            @endif
                            
                                
                    </div>
                    
                    @elseif(auth()->user()->hasRole('teacher') ||  auth()->user()->hasRole('live teacher'))
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-3 col-12 border-right">
                                    <div class="row">

                                    @if(auth()->user()->hasRole('teacher'))    

                                        <div class="col-12">
                                            <div class="card text-white bg-primary text-center">
                                                <div class="card-body">
                                                    <h2 class="">{{count(auth()->user()->courses) + count(auth()->user()->bundles)}}</h2>
                                                    <h5>@lang('labels.backend.dashboard.your_courses_and_bundles')</h5>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="card text-white bg-success text-center">
                                                <div class="card-body">
                                                    <h2 class="">{{$students_count}}</h2>
                                                    <h5>@lang('labels.backend.dashboard.students_enrolled')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(auth()->user()->hasRole('live teacher'))
                                        <div class="col-12">
                                            <div class="card text-white bg-primary text-center">
                                                <div class="card-body">
                                                    <h2 class="">{{ $count_courses }}</h2>
                                                    <h5>Your Live Courses</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="card text-white bg-success text-center">
                                                <div class="card-body">
                                                    <h2 class="">{{$live_students_count}}</h2>
                                                    <h5>Students Enrolled To Your Live Courses</h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    </div>
                                </div>
                                <div class="col-md-5 col-12 border-right">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_reviews') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.reviews.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>

                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.course')</td>
                                            <td>@lang('labels.backend.dashboard.review')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count(@$recent_reviews) > 0  || count(@$recent_live_reviews) > 0 )
                                            @if(auth()->user()->hasRole('teacher') && count($recent_reviews) > 0 )
                                                @foreach($recent_reviews as $item)
                                                    <tr>
                                                        <td>
                                                            <a target="_blank"
                                                               href="{{route('courses.show',[$item->reviewable->slug])}}">{{$item->reviewable->title}}</a>
                                                        </td>
                                                        <td>{{$item->content}}</td>
                                                        <td>{{$item->created_at->diffforhumans()}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            @if(auth()->user()->hasRole('live teacher') && count(@$recent_live_reviews) > 0 )

                                            @if(array_key_exists("reviewable",$recent_live_reviews))
                                                @foreach(@$recent_live_reviews as $item)
                                                    <tr>
                                                        <td>
                                                          <a target="_blank"
                                                               href="{{route('courses.show',[$item->reviewable->slug])}}">{{$item->reviewable->title}}</a>
                                                        </td>
                                                        <td>{{$item->content}}</td>
                                                        <td>{{$item->created_at->diffforhumans()}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_messages') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.messages')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>


                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.message_by')</td>
                                            <td>@lang('labels.backend.dashboard.message')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($threads) > 0)
                                            @foreach($threads as $item)
                                                <tr>
                                                    @if($item->lastMessage['body'])
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{asset('/user/messages/?thread='.$item->id)}}">{{$item->title}}</a>
                                                    </td>
                                                    <td>{{$item->lastMessage->body}}</td>
                                                    <td>{{$item->lastMessage->created_at->diffForHumans() }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    @elseif(auth()->user()->hasRole('administrator'))
                        <div class="col-md-4 col-12">
                            <div class="card text-white bg-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$courses_count}}</h1>
                                    <!-- <h3>@lang('labels.backend.dashboard.course_and_bundles')</h3> -->
                                    <h3>Courses</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$students_count}}</h1>
                                    <h3>@lang('labels.backend.dashboard.students')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card text-white bg-primary text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$teachers_count}}</h1>
                                    <h3>@lang('labels.backend.dashboard.teachers')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card text-white bg-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$live_courses_count}}</h1>
                                    <h3>Live Courses</h3>
                                </div>
                            </div>
                        </div>

         
                        <div class="col-md-4 col-12">
                            <div class="card text-white bg-light text-dark text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$ofline_students_count}}</h1>
                                    <h3>Ofline Students</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="card text-white bg-primary text-center py-3">
                                <div class="card-body">
                                    <h1 class="">{{$live_teachers_count}}</h1>
                                    <h3>Live Teacher</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 border-right">
                            <div class="d-inline-block form-group w-100">
                                <h4 class="mb-0">@lang('labels.backend.dashboard.recent_orders') <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.orders.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                </h4>

                            </div>
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <td>@lang('labels.backend.dashboard.ordered_by')</td>
                                    <td>@lang('labels.backend.dashboard.amount')</td>
                                    <td>@lang('labels.backend.dashboard.time')</td>
                                    <td>@lang('labels.backend.dashboard.view')</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($recent_orders) > 0)
                                    @foreach($recent_orders as $item)
                                        <tr>
                                            <td>
                                               {{$item->user->name}} 
                                            </td>
                                            <td>{{$item->amount.' '.$appCurrency['symbol']}}</td>
                                            <td>{{$item->created_at->diffforhumans()}}</td>
                                            <td><a class="btn btn-sm btn-primary"
                                                   href="{{route('admin.orders.show', $item->id)}}" target="_blank"><i
                                                            class="fa fa-arrow-right"></i></a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">@lang('labels.backend.dashboard.no_data')</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="d-inline-block form-group w-100">
                                <h4 class="mb-0">@lang('labels.backend.dashboard.recent_contact_requests') <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.contact-requests.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                </h4>

                            </div>
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <td>@lang('labels.backend.dashboard.name')</td>
                                    <td>@lang('labels.backend.dashboard.email')</td>
                                    <td>@lang('labels.backend.dashboard.message')</td>
                                    <td>@lang('labels.backend.dashboard.time')</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($recent_contacts) > 0)
                                    @foreach($recent_contacts as $item)
                                        <tr>
                                            <td>
                                                {{$item->name}}
                                            </td>
                                            <td>{{$item->email}}</td>
                                            <td>{{$item->message}}</td>
                                            <td>{{$item->created_at->diffforhumans()}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">@lang('labels.backend.dashboard.no_data')</td>

                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- <div class="col-12">
                            <h1>@lang('labels.backend.dashboard.title')</h1>
                        </div> -->
                    @endif
                </div>
            </div><!--card-body-->
        </div><!--card-->
    </div><!--col-->
@endsection

@push('after-scripts')
    <script>
    	$('.dismiss_msg').click(function(){
    		$(this).parent().css('display','none');
    	})
    </script>
@endpush