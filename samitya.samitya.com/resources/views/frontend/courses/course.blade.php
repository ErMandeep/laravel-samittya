@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($course->meta_title) ? $course->meta_title : app_name() )
@section('meta_description', $course->meta_description)
@section('meta_keywords', $course->meta_keywords)

@push('after-styles')
    <style>
        .leanth-course.go {
            right: 0;
        }
.gift {
    margin: 40px 0px;
    fill: #fd2f70;
    width: 30px;
    cursor: pointer;
}
.countine button {
    width: 180px;
    color: #ffffff !important;
    font-size: 12px;
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
.no_sub {
    font-size: 21px !important;
    padding: 15px 10px;
    font-weight: 700;
}

.st-custom-button[data-network] {
  background-color: transparent;
    display: inline-block;
    padding: 0;
   /*padding: 5px 10px;*/
   cursor: pointer;
   font-weight: bold;
   color: #fff;
   
   &:hover, &:focus {
      text-decoration: underline;
      background-color: #00c7ff;
   }


    </style>
@endpush

@section('content')

<!-- Start of course details section
    ============================================= -->
<section id="course-details">
    <section class="mk">
      <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-dismissable alert-success fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{session('success')}}
            </div>
        @endif
        <div class="row mk-baner">
          
          @if($course->videolink)
          <video  id="vid1" class="video-js vjs-default-skin"   autoplay   data-setup='{ "techOrder": ["vimeo"], "sources": [{ "type": "video/vimeo", "src": "{{ $course->videolink }}"}] } '
          >
          </video>
          @else
          <div class="col-md-7 banner-section text-center " style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')">
            <!-- <img src="{{asset('assets/images/videoicon.png')}}"> -->
            <img class="play-button"  src="{{asset('assets/images/play-button.png')}}">
          </div>
          @endif
          
           <div class="col-md-5 mk-bor">

           @foreach($lessons as $lesson )
            <li>
              <a href="{{route('lessons.show',['id' => @$lesson->model->course_id,'slug'=>@$lesson->model->slug])}}" >
                <span class="les_title">
                  {{@$lesson->model->title}}
                </span>
              </a>
              @if(@!$lesson->model->preview)
                <span class="mk-span">
                  <!-- <img src="{{ asset('assets/images/lock.svg') }}"> -->
                  <i class="fas fa-lock"></i>
                </span>
              @endif
              <div class="hrs">
               <!-- {{ @$lesson->model->display_duration}} -->
@if(@$lesson->model->display_duration)
@php
$part = preg_split('/\s+/', $lesson->model->display_duration);

if (strpos($lesson->model->display_duration, 'hour') !== false) {
$part[1] = "hour";
}else{
  
$part[1] = "mins";

}

$final = implode(' ', $part);
@endphp
{{@$final}}
@endif

              </div>
            </li>
           @endforeach
           </div>          

         </div>
      </div>
    </section>
    <div class="container coursecontainer">
        <div class="row">
            <div class="col-md-7 mk-bor-2">
                <div class="row">
                    <div class="col-md-10">
                          <h2>{{$course->title}}</h2>
                            <h4>
                            @if($course->language_used)
                            Course language-{{$course->language_used}} 
                            @endif
                            @if($course->display_duration)
                            <span class="new-hrs lock">
                              {{$course->display_duration}}
                            </span>
                            @endif
                            </h4>
                          <!-- <p>
                            {{  $course->description }}
                          </p> -->
                          <div class="thumbnail">
                            
                          <img src="{{ $teacher->picture  }}" class="mk-img-radi">
                          </div>
                          <h3 class="mk-h3">
                            {{$teacher->full_name}}
                          </h3>
                          @if($teacher->description)
                          <h5>
                            {{$teacher->full_name}} Bio
                          </h5>   
                          @endif          
                    </div>
                    <div class="col-md-5 ">
                        <div class="rev">
                            <!-- <span class="star"><img src="images/filled-star.png"></span> -->
                             <span class="star">
                                  <ul class="mk-star">
                                    @for($r=1; $r<=round($course_rating); $r++)
                                        <li><img src="{{asset('assets/images/rating-star.png')}}"></li>
                                    @endfor
                                    @for($r=round($course_rating)+1 ; $r<=5; $r++)
                                        <li><img src="{{asset('assets/images/blank-star.png')}}"></li>
                                    @endfor                           
                                  </ul>
                             </span>
                            <span class="rev-am">
                              ({{count($course->reviews)}} reviews)
                            </span>
                        </div>
                    </div>
                    <div class="container mk-pad-20">
                        <p>{{$teacher->description}} </p>
                    </div>
                </div>
                         @if($course->description)
                          <div class="details">
                            <p class="title">Course detail</p>
                            <p class="text">{{ $course->description }}</p>
                          </div>   
                          @endif  
                          @if($course->requirement)
                          <div class="details">
                            <p class="title">Requirement</p>
                            <p class="text">{{ $course->requirement }}</p>
                          </div>   
                          @endif  
            </div>
            <div class="col-md-5 mk-bor-2">
              @if($course->downloadable_files)

              <p class="mk-dc">Supporting document 
                <div class="doc">
                    <a class="doc" href="{{route('admin.invoice.downloadfiles',['course'=>$course->downloadable_files])}}">
                    </a>
                </div>
              </p>
              @endif

@if(@$course->free == 1)

                  <div class="row mk-pad">
     
                          @if(!$purchased_course)
                              @if(!auth()->check())
                                  <!--   <a id="openLoginModal"
                                    class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font"
                                    data-target="#myModal" href="#">Subscribe
                                    </a> -->

                    <div class="col-md-4 ">
                      
                   
          <div class="countine">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf                                     
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="sub_plan" value="4">
                <input type="hidden" name="amount" value="free">
                <!-- <input type="hidden" name="free" value="1"> -->
                  <div class="countine"> 
                                          <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Enroll Now
                </button>
                                    
                        </div>
            </form>
                  </div>

                  </div>

                                  @elseif(auth()->check() )

                    <div class="col-md-4 ">
                      
                   
          <div class="countine">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf                                     
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="sub_plan" value="4">
                <input type="hidden" name="amount" value="free">
                <!-- <input type="hidden" name="free" value="1"> -->
                  <div class="countine"> 
                                          <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Enroll Now
                </button>
                                    <!-- <a href=""
                                           class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font">
                                            Enroll Now
                                            <i class="fa fa-arrow-right"></i>
                                    </a> -->
                        </div>
            </form>
                  </div>

                  </div>


                              @endif
                          @else
                       
                              @if($continue_course)

                      <div class="col-md-4">
                        <div class="countine">     
                                    <a href="{{route('lessons.show',['id' => @$course->id,'slug'=>@$continue_course->model->slug])}}"
                                           class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font">
                                            @lang('labels.frontend.course.continue_course')
                                            <i class="fa fa-arrow-right"></i>
                                    </a>
                        </div>
                    </div>
                              @endif
                          @endif




                  </div>

@else


                  <div class="row mk-pad">
     
                          @if(!$purchased_course)
                          @if( Auth::check() && $logged_in_user->hasRole('supervisor'))
               
                <div class="free_trail">
                <h4 class="no_sub">You Can not Subscribe</h4>
                </div>
                          
                          @endif
@if( Auth::check() && $logged_in_user->hasRole('supervisor'))

@else
                             
                    <div class="col-md-4 sub_btn">
                      <h3>{{$course->sub_month_1}} months</h3>
                    <h5>{{$appCurrency["symbol"]}}{{$course->sub_price_1}}/month*</h5>
          <div class="sub_price">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf                                     
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="sub_plan" value="1">
                <input type="hidden" name="amount" value="{{ $course->sub_price_1}}">
                <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Subscribe 
                </button>
            </form>
                  </div>

                  </div>
                    <div class="col-md-4 sub_btn">
                      <h3 class="popular_trial_wrapper">{{$course->sub_month_2}} months</h3>
                      <span class="trial_popular_text">Popular</span>
                    <h5>{{$appCurrency["symbol"]}}{{$course->sub_price_2}}/month*</h5>
          <div class="sub_price">
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf                     
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="sub_plan" value="2">
                <input type="hidden" name="amount" value="{{ $course->sub_price_2}}">
                <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Subscribe 
                </button>
            </form>
                  </div>
                  </div>
                    <div class="col-md-4 sub_btn">
                      <h3>{{$course->sub_month_3}} months</h3>
                    <h5>{{$appCurrency["symbol"]}}{{$course->sub_price_3}}/month*</h5>

          <div class="sub_price">
            <form action="{{ route('cart.checkout') }}" method="POST">
              
                @csrf     
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="sub_plan" value="3">
                <input type="hidden" name="amount" value="{{ $course->sub_price_3}}">
                <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Subscribe 
                </button>
            </form>
                  </div>
                  </div>  

                              @endif
                          @else
                       
                              @if($continue_course)

                      <div class="col-md-4">
                        <div class="countine">     
                                    <a href="{{route('lessons.show',['id' => @$course->id,'slug'=>@$continue_course->model->slug])}}"
                                           class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font">
                                            @lang('labels.frontend.course.continue_course')
                                            <i class="fa fa-arrow-right"></i>
                                    </a>
                        </div>
                    </div>
                              @endif
                          @endif




                  </div>
  @endif                
               <!-- ***********new gift***************    -->
@if( Auth::check() && $logged_in_user->hasRole('supervisor'))
@else
<div class="container">
  <a href="#" data-toggle="modal" data-target="#checkemail" >
<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
width="50px" height="50px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve" class="gift">
<path d="M37.846,13C40.773,11.734,43,9.311,43,6c0-3.131-1.988-5-5.319-5c-4.212,0-6.188,3.411-7.931,6.42
C28.327,9.877,27.098,12,25,12c-2.098,0-3.328-2.123-4.751-4.58C18.506,4.411,16.53,1,12.319,1C8.988,1,7,2.869,7,6
c0,3.311,2.227,5.734,5.155,7H1v11h2v25h44V24h2V13H37.846z M31.481,8.423C33.097,5.634,34.622,3,37.681,3C39.883,3,41,4.009,41,6
c0,3.756-4.067,6-8,6h-3.913C29.999,10.981,30.746,9.692,31.481,8.423z M9,6c0-1.991,1.117-3,3.319-3c3.059,0,4.584,2.634,6.2,5.422
C19.253,9.692,20,10.981,20.913,12H17C13.067,12,9,9.756,9,6z M19,47H5V24h14V47z M19,22H3v-7h16V22z M29,47h-8V15h8V47z M45,47H31
V24h14V47z M47,22H31v-7h16V22z"/>
</svg></a>
</div>
@endif

<div class="">
<h4>Share this course</h4>
<ul class="mk-social-ul">
<li><a href="https://www.facebook.com/sharer/sharer.php?u={{url()->current()}}" target="_blank">
  <img  src="{{asset('assets/images/facebook-icon.png')}}" class="mk-social st-custom-button ">
</a>
  </li>
<li><a href="https://twitter.com/share?text={{url()->current()}}&url={{$course->title}}" target="_blank"> <img  src="{{asset('assets/images/twiter-icon.png')}}" class="mk-social st-custom-button"></a></li>
<li><a href="https://api.whatsapp.com/send?phone=&text={{url()->current()}}" target="_blank"><img src="{{asset('assets/images/whatsapp-icon.png')}}" class="mk-social st-custom-button"></a></li>
</ul>
</div>
                        

            </div>
        </div>
    </div>



    <section class="offer-section mk-mar">
          <div class="container">

            @if($course->category_id == env('live_class_id'))
            <div class="live_faq">
              @if(count($faqs)> 0)
                  <h4 class="faq_heading" >Frequently asked questions</h4>
                  <div class="faq-tab">
                      <div class="faq-tab-ques ul-li">
                          <div class="tab-container">
                              @foreach($faqs as $item)
                                <div class="faq" data-toggle="collapse" data-target="#{{$item->id}}">
                                  <p class="faq_questions">{{$item->question}}<i class="fas fa-sort-down"></i></p>
                                  
                                </div>
                                <div id="{{$item->id}}" class="collapse">
                                  <p class="faq_answer">{{$item->answer}}</p>
                                </div>
                              @endforeach
                          </div>
                      </div>
                  </div>
              @endif
            </div>
            @endif

            <div class="row mt100">

              @if($course->category_id !== env('live_class_id'))
                  <div class="col-md-12">
                    <h2>Other courses from {{$teacher->full_name}}</h2>
                  </div>

              @if(count($teacher->courses) > 0)
                  <div class="col-md-12">
                      <div class="row other_courses">
                          @foreach($teacher->courses->take(6) as $item)
                            <div class="card col-md-4"> 
                                <a href="{{ route('courses.show', [$item->slug]) }}">
                                    <div class="thumbnailBox">
                                        <div class="heart"><img src="{{asset('assets/images/heart.svg')}}"></div>
                                        <div class="thumbPic-wrapper" >
                                          <div class="thumbPic" style="background-image: url({{ asset('storage/uploads/'.$item->course_image) }})">
                                              <div class="videobutton">
                                                <img src="{{asset('assets/images/play-icon.png')}}">
                                              </div>
                                          </div>
                                             <div class="thumb-content">
                                                  <div class="rev">
                                                   <!--  Rating By theme -->
                                                    <span class="star ">

                                                            @for($r=1; $r<=round($item->reviews->avg('rating')); $r++)
                                                                <img src="{{asset('assets/images/rating-star.png')}}">
                                                            @endfor
                                                            @for($r=round($item->reviews->avg('rating'))+1 ; $r<=5; $r++)
                                                                <img src="{{asset('assets/images/blank-star.png')}}">
                                                            @endfor
                                                    </span>
                                                    <span class="rev-am">({{count($item->reviews) }} reviews)</span>
                                                  </div>
                                                <div class="review-price">
                                                    <div class="price-amount"> 
                                                      <div class="language">
                                                          <div class="lan" >
                                                            {{ $item->title }}  
                                                          </div>
                                                          @if($item->display_duration)
                                                          <div class="hrs">
                                                            {{ $item->display_duration }}
                                                          </div>
                                                          @endif
                                                          <div class="price">
                                                            <div class="Currency" >
                                                              {{$appCurrency['symbol'].$item->price }}
                                                            </div>
                                                            <button> Subscribe</button> 
                                                          </div>
                                                        </div>
                                                      </div> 
                                                  </div>
                                                </div>
                                        </div>
                                        @if($item->popular)
                                        <div class="popular">Popular</div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                          @endforeach
                      </div>
                      <hr/>
                  </div>
              @else
                <p>@lang('labels.general.no_data_available')</p>
              @endif
              <!-- <div class="col-md-4"></div> -->
              @else
              <div class="col-md-12">
                    <h2>Other Live classes you may be interested in.</h2>
                </div>
              <div class="col-md-12">
                    <div class="row other_courses">
                        @foreach($livecourses->take(6) as $item)
                          <div class="card col-md-4"> 
                              <a href="{{ route('courses.show', [$item->slug]) }}">
                                  <div class="thumbnailBox">
                                      <div class="heart"><img src="{{asset('assets/images/heart.svg')}}"></div>
                                      <div class="thumbPic-wrapper" >
                                        <div class="thumbPic" style="background-image: url({{ asset('storage/uploads/'.$item->course_image) }})">
                                            <div class="videobutton">
                                              <img src="{{asset('assets/images/play-icon.png')}}">
                                            </div>
                                        </div>
                                           <div class="thumb-content">
                                                <div class="rev">
                                                 <!--  Rating By theme -->
                                                  <span class="star ">

                                                          @for($r=1; $r<=round($item->reviews->avg('rating')); $r++)
                                                              <img src="{{asset('assets/images/rating-star.png')}}">
                                                          @endfor
                                                          @for($r=round($item->reviews->avg('rating'))+1 ; $r<=5; $r++)
                                                              <img src="{{asset('assets/images/blank-star.png')}}">
                                                          @endfor
                                                  </span>
                                                  <span class="rev-am">({{count($item->reviews) }} reviews)</span>
                                                </div>
                                              <div class="review-price">
                                                  <div class="price-amount"> 
                                                    <div class="language">
                                                        <div class="lan" >
                                                          {{ $item->title }}  
                                                        </div>
                                                        @if($item->display_duration)
                                                        <div class="hrs">
                                                          {{ $item->display_duration }}
                                                        </div>
                                                        @endif
                                                        <div class="price">
                                                          <div class="Currency" >
                                                            {{$appCurrency['symbol'].$item->price }}
                                                          </div>
                                                          <button> Subscribe</button> 
                                                        </div>
                                                      </div>
                                                    </div> 
                                                </div>
                                              </div>
                                      </div>
                                      @if($item->popular)
                                      <div class="popular">Popular</div>
                                      @endif
                                  </div>
                              </a>
                          </div>
                        @endforeach
                    </div>
                    <hr/>
              </div>
              @endif

            </div>


          </div>
    </section>
    <section class="offer-section mk-mar reviews">
        
          <div class="container">         
              <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <div class="rating-block">
                        <h4>Average user rating</h4>
                        <h2 class="bold padding-bottom-7">{{ round($course_rating) }} <small>/ 5</small></h2>
                        @for($r=1; $r<=round($course_rating); $r++)
                           <button type="button" class="btn btn-warning btn-sm" aria-label="Left Align">
                          <i class="fas fa-star"></i>
                        </button>
                        @endfor
                        @for($r=round($course_rating)+1; $r<=5; $r++)
                            <button type="button" class="btn btn-default btn-grey btn-sm" aria-label="Left Align">
                          <i class="fas fa-star"></i>
                        </button>
                        @endfor                    
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <h4>Rating breakdown</h4>
                    @for($r=5; $r>=1; $r--)
                        <div class="pull-left">
                        <div class="pull-left" style="width:60px; line-height:1;">
                            <div style="height:9px; margin:5px 0;">{{$r}} @lang('labels.frontend.course.stars')</div>
                        </div>
                        @if(count($course->reviews) > 0)
                         @php
                          
                          $process_bar = round( ( $course->reviews()->where('rating','=',$r)->get()->count() /  count($course->reviews) )*100 )

                         @endphp

                        @else
                         @php $process_bar = 0 @endphp
                        @endif
                        <div class="pull-left" style="width:150px;">
                            <div class="progress" style="height:9px; margin:8px 0;">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: {{ $process_bar  }}%">
                                <span class="sr-only">80% Complete (danger)</span>
                              </div>
                            </div>
                        </div>
                        <div class="pull-right" style="margin-left:10px;">{{ $process_bar  }}%</div>
                    </div>
                    @endfor
                </div>          
              </div> 
            @if($is_reviewed == false && auth()->check())
            <div class="row mk-comment">
                <div class="col-md-12">
                  <p>  Did you take this course? Share your experience with other students.</p>
                </div>
                <div class="col-md-12">
                    <button class="mk-btn-review" data-toggle="modal" data-target="#reviewmodal">
                     <i class="fas fa-star"></i> WRITE A REVIEW
                    </button>
                 </div>
            </div>
            @endif
            <div class="row">
                <div class="col-sm-12 col-md-7">
                    
                    <div class="review-block">
                      @if(count($course->reviews) > 0)
                                       @foreach($course->reviews as $item)
                                            <div class="row mk-row">
                                              <div class="col-sm-3 col-md-3">
                                                  <img src="{{$item->user->picture}}" class="img-rounded">
                                                  <div class="review-block-name">{{$item->user->full_name}}</div>
                                                  <div class="review-block-date">{{$item->created_at->diffforhumans()}}</div>
                                              </div>
                                              <div class="col-sm-9 col-md-9">
                                                  <div class="review-block-rate">
                                                    @for($i=1; $i<=(int)$item->rating; $i++)
                                                       <button type="button" class="btn btn-warning btn-xs" aria-label="Left Align">
                                                        <i class="fas fa-star"></i>
                                                      </button>
                                                    @endfor
                                                    @for($i=(int)$item->rating+1; $i<=5; $i++)
                                                        <button type="button" class="btn btn-default btn-grey btn-xs" aria-label="Left Align">
                                                       <i class="fas fa-star"></i>
                                                      </button>
                                                    @endfor
                                                    @if(auth()->check() && ($item->user_id == auth()->user()->id))
                                                            <div>
                                                                <a href="{{route('courses.review.edit',['id'=>$item->id])}}"
                                                                    class="mr-2">@lang('labels.general.edit')</a>
                                                                <a href="{{route('courses.review.delete',['id'=>$item->id])}}"
                                                                   class="text-danger">@lang('labels.general.delete')</a>
                                                            </div>
                                                        @endif
                                                    
                                                  </div>
                                                  <div class="review-block-description">{{$item->content}}</div>
                                              </div>
                                          </div>

                                        @endforeach
                                @else
                                    <h4> @lang('labels.frontend.course.no_reviews_yet')</h4>
                                @endif
                                

                    </div>
                </div>
            </div>
            
          </div> <!-- /container -->
    </section>
</section>
    
<!-- Modal -->
<div id="reviewmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <!-- <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Review</h4>
      </div> -->
      <div class="modal-body">
        <div class="review-stars-item float-right mt15">
                                                <span>@lang('labels.frontend.course.your_rating'): </span>
                                                <div class="rating">
                                                    <label>
                                                        <input type="radio" name="stars" value="1"/>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" value="2"/>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" value="3"/>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" value="4"/>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="stars" value="5"/>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                        <span class="icon"><i class="fas fa-star"></i></span>
                                                    </label>
                                                </div>
                                            </div>
                            <div class="teacher-faq-form">
                                            @php
                                                if(isset($review)){
                                                    $route = route('courses.review.update',['id'=>$review->id]);
                                                }else{
                                                    $route = route('courses.review',['course'=>$course->id]);
                                                }
                                            @endphp
                                            <form method="POST"
                                                  action="{{$route}}"
                                                  data-lead="Residential">
                                                @csrf
                                                <input type="hidden" name="rating" id="rating">
                                                <label for="review">@lang('labels.frontend.course.message')</label>
                                                <textarea name="review" class="mb-2" id="review" rows="2"
                                                          cols="20">@if(isset($review)){{$review->content}} @endif</textarea>
                                                <span class="help-block text-danger">{{ $errors->first('review', ':message') }}</span>
                                                <div class="nws-button text-center  gradient-bg text-uppercase">
                                                    <button type="submit" value="Submit">@lang('labels.frontend.course.add_review_now')
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>
<!-- //Modal -->

<!-- End of course details section
    ============================================= -->
    <div class="modal fade" id="checkemail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
               

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane container active" >

                            <span class="error-response text-danger"></span>
                            <span class="success-response text-success"></span>


     {!! Form::open(['method' => 'POST', 'route' => ['gift.checkemail'], 'files' => true,]) !!}
                                
                                <div class="contact-info mb-2">
                                    <?php echo e(html()->email('email')
                                        ->class('form-control mb-0')
                                        ->placeholder('Enter Recipient Email')
                                        ->attribute('maxlength', 191)); ?>
                                </div>
                                <div class="contact-info mb-2">
                                  <select class="form-control mb-0" name="amount" id="amount"> 
                                    <option value="0" disabled selected > Select The Subscription </option>
                                    <option value="{{  $course->sub_price_1 }}" data-sub_plan ="1">{{$appCurrency["symbol"]}} {{$course->sub_price_1}} ({{$course->sub_month_1}} months)</option>
                                    <option value="{{ $course->sub_price_2}}" data-sub_plan="2">{{$appCurrency["symbol"]}}{{$course->sub_price_2}} ({{$course->sub_month_2}} months)</option>
                                    <option value="{{ $course->sub_price_3}}" data-sub_plan="3">{{$appCurrency["symbol"]}}{{$course->sub_price_3}} ({{$course->sub_month_3}} months)</option>
                                  </select>
                                  <input type='hidden' id="name" name="sub_plan" value=""/>
                                  <input type="hidden" name="course_id" value="{{ $course->id }}">      
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
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>

    <script>

    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

      twttr.ready(function (twttr) {
        twttr.events.bind('tweet', function (event) {
        // alert("paso");
      });

    });

        $(document).on('change', 'input[name="stars"]', function () {
            $('#rating').val($(this).val());
        })
                @if(isset($review))
        var rating = "{{$review->rating}}";
        $('input[value="' + rating + '"]').prop("checked", true);
        $('#rating').val(rating);
        $("#reviewmodal").modal("show");
        @endif



  $(document).on('change','#amount',function(){
    var dataid = $('option:selected', this).data('sub_plan');
    $('#name').val(dataid);
});
    $('#checkemail').on('shown.bs.modal', function () {
            $('#amount').val(0); 
        });

    </script>

        <script>
        $(document).ready(function () {

        // $( "#Budgetlevel" ).selectmenu();
        $("#amount").select2({
          placeholder: "Select Budget",
          minimumResultsForSearch: Infinity,

        });

        

});
</script>
@endpush


<!-- *************************modal********************************* -->


<!-- ************************ -->
