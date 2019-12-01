@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($course->meta_title) ? $course->meta_title : app_name() )
@section('meta_description', $course->meta_description)
@section('meta_keywords', $course->meta_keywords)

@push('after-styles')
    <style>
    .leanth-course.go {
          right: 0;
    }
    .col-md-8.banner-section {
      padding: 215px !important;
      background-color: #eee;
    }
    /*.live_class_details {
      padding: 100px 0px;
      position: relative;
    }
    .live_class_details .far, .live_class_details .fas {
		font-size: 45px;
		background: transparent;
		color: #3D2D48;
		vertical-align: middle;
	}*/


.mk-pad {
    padding: 0px 5px;
}

.price a, .price button{
	padding: 10px;
}
.sub_btn{
	text-align: center;
	padding: 0;
}
@media (min-width: 1200px){
.container {
    max-width: 1300px;
}
}
/*.details .title {
	color: #3D2D48;
    font-family: Varela Round;
    font-size: 20px;
    font-weight: bold;
    font-style: normal;
    text-decoration: none;
    text-align: center;
    background: rgba(255,255,255,0.9);
    border: 0px solid #BDBDBD;
    border-radius: 60px;
    box-shadow: 0 0 5px #333;
    width: 36%;
}
.web_cam{
	    fill: #fc6c46;
}*/
.gift{
  	margin: 40px 0px;
  	fill: #fd2f70;
  	width: 30px;
  	cursor: pointer;
}
/*booking .week{
    max-width: 14.285714285714285714285714285714% !important;
    float: left;
    padding: 0px 13px;
}
.booking .day {
    font-weight: 700;
    text-align: center;
}*/
.free_trail .no_sub{
  font-size: 21px;
    padding: 15px 10px;
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
        <div class="row mk-baner right_section">
          
          @if($course->videolink)
          <video  id="vid1" class="video-js vjs-default-skin col-md-7"   autoplay   data-setup='{ "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "https://www.youtube.com/watch?v=d658ndSWg6c"}] ,  "youtube": { "ytControls": 2 } }'
          >
          </video>
          <!-- <iframe  src="{{ $course->videolink }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>  -->        
           @else
          <div class="col-md-8 banner-section text-center " style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')">
            <img src="{{asset('assets/images/videoicon.png')}}">
          </div>
          @endif
          

           <div class="col-md-5 live_class_details">
            <div class="row">
              <div class="level">
                    {{ $course->skills_level }}
              </div>
              <div class="content row">
                <div class="col-6" >
                <!-- <i class="far fa-clock"></i> -->
                <img src="{{ asset('assets/images/ic_access_time.png') }}">

                <!-- <span class="text">{{ $course->duration }}</span> -->
                <span class="text">1 Hr</span>
                <p class="small_text">Class Duration</p>
                </div>
                <div class="col-6" >
                  <!-- <i class="fas fa-user-friends"></i> -->
                  <img src="{{ asset('assets/images/ic_people_outline.png') }}">
                  <span class="text">{{ $course->per_batch }}</span>
                  <p class="small_text">Per batch</p>
                </div>
                <div class="col-6" >
                  <!-- <i class="fas fa-microphone"></i> -->
                  <img src="{{ asset('assets/images/ic_mic.png') }}">
                  <span class="text">{{ $course->language_used }}</span>
                  <p class="small_text">Course language</p>
                </div>
                <div class="col-6" >
                  <!-- <i class="fas fa-desktop"></i> -->
                  <img src="{{ asset('assets/images/ic_live_tv.png') }}">
                  <span class="text">Skype</span>
                  <p class="small_text">Software</p>
                </div>
              </div>
              
              @if($is_trial_taken  )
                <div class="free_trail">
                <h4 class="trial_price">You have already taken trial lesson with this teacher</h4>
              </div>
              @elseif($total_purchase || $purchased_course)

              @else
              @if( Auth::check() &&  $logged_in_user->hasRole('supervisor'))
              @else
               <div class="free_trail">
                  @if($course->trial_price)
                  <h4 class="trial_price">{{ $appCurrency["symbol"].' '.$course->trial_price }}*</h4>
                  @else
                  <h4 class="trial_price">Free*</h4>
                  @endif

                  @if(!auth()->check())
                     <a id="openLoginModal" class="book_btn" data-target="#myModal" href="#">Book trial lesson</a>
                  @else
                    <button class="book_btn" data-toggle="modal" data-target="#trial_book">
                        Book trial lesson
                    </button>
                  @endif
                    <p class="trial_note">* For one trial lesson</p>
              </div>
              
              @endif
              @endif

              
            
           </div>
          

         </div>
      </div>
    </section>
    <div class="container coursecontainer">
        <div class="row">
            <div class="col-md-7 mk-bor-2">
                <div class="row">
                    <div class="col-md-10">
                      	<h2>{{$course->title}}
	                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" id="Layer_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50"  xml:space="preserve" class="web_cam">
					            <g>
					              <path d="M33,22c0-4.411-3.589-8-8-8s-8,3.589-8,8s3.589,8,8,8S33,26.411,33,22z M19,22c0-3.309,2.691-6,6-6s6,2.691,6,6   s-2.691,6-6,6S19,25.309,19,22z"/>
					              <path d="M37,22c0-6.617-5.383-12-12-12c-6.617,0-12,5.383-12,12c0,6.617,5.383,12,12,12C31.617,34,37,28.617,37,22z M15,22   c0-5.514,4.486-10,10-10c5.514,0,10,4.486,10,10c0,5.514-4.486,10-10,10C19.486,32,15,27.514,15,22z"/>
					              <path d="M36.908,39.278C42.394,35.485,46,29.159,46,22c0-11.58-9.421-21-21-21C13.42,1,4,10.42,4,22   c0,7.159,3.606,13.485,9.092,17.278C9.802,40.06,6.051,41.499,6.051,44c0,3.905,9.533,5.948,18.949,5.948S43.948,47.905,43.948,44   C43.948,41.497,40.186,40.058,36.908,39.278z M6,22C6,11.523,14.523,3,25,3s19,8.523,19,19s-8.523,19-19,19S6,32.477,6,22z    M25,48.052c-11.195,0-17.051-2.638-17.051-4.052c0-0.761,2.065-2.314,7.572-3.286C18.373,42.164,21.588,43,25,43   c3.413,0,6.628-0.836,9.479-2.287c5.509,0.973,7.572,2.528,7.572,3.287C42.052,45.414,36.195,48.052,25,48.052z"/>
					            </g>
					          </svg>
				      	</h2>
                          <img src="{{ $teacher->picture }}" class="mk-img-radi">
                          @if( Auth::check() &&  $logged_in_user->hasRole('supervisor'))

                          @else
                          <a href="{{ route('admin.messages').'?teacher_id='.$teacher->id }}" class="send_message">
                              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                                  <path d="M40 4H8C5.79 4 4.02 5.79 4.02 8L4 44l8-8h28c2.21 0 4-1.79 4-4V8c0-2.21-1.79-4-4-4zm-4 24H12v-4h24v4zm0-6H12v-4h24v4zm0-6H12v-4h24v4z"></path>
                                  <path d="M0 0h48v48H0z" fill="none"></path>
                              </svg>  Send Message
                          </a>
                          @endif
                          <h3 class="mk-h3">
                            {{$teacher->full_name}}
                          </h3>
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
	                               ({{ count($course->reviews)}} reviews)
	                            </span>
	                        </div>
	                        <div style="clear: both;"></div>
                          @if($teacher->description)
                          <h5>
                            {{$teacher->full_name}} Bio
                          </h5>  
                          <div class="container mk-pad-20">
	                        <p>{{$teacher->description}} </p>
	                        <!-- <p>Here will Come the teacher Bio</p> -->
	                    </div> 
                          @endif  
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
                    <div class="col-md-4 mk-right-left">
                        
                    </div>
                    
                </div>
            </div>
            <div class="col-md-5 mk-bor-2">
                  <div class="row mk-pad">
                    <div class="calander col-sm-12">
                      <div class="calander_icon">
                    		<!-- <i class="far fa-calendar-alt"></i> -->
                        <img src="{{ asset('assets/images/calendar.png') }}">
                    	</div>
                      <div class="schedule">
                      	
	                      	<li>
	                      		<p class="day">Mon</p>
                            @if(@$schedule[0]['mon'])
	                      		   @php
          							         $start_time = date('h ',strtotime($schedule[0]['mon_start_time']) ); 
          							         $end_time = date('h A',strtotime($schedule[0]['mon_end_time']) );
							                 @endphp
	                      		   <p class="time">{{$start_time.' - '.$end_time}}</p>

                            @else
                                <p class="time"><span>-</span></p>
                    	      @endif

	                      	</li>
	                      	<li>
	                      		<p class="day">Tue</p>
                      	@if(@$schedule[0]['tue'])
	                      		   @php
							         $start_time = date('h ',strtotime($schedule[0]['tue_start_time']) ); 
							         $end_time = date('h A',strtotime($schedule[0]['tue_end_time']) );
							       @endphp
	                      		<p class="time">{{$start_time.' - '.$end_time}}</p>
                          @else
                          <p class="time"><span>-</span></p>
                      	@endif

	                      	</li>
	                      	<li>
	                      		<p class="day">Wed</p>
                      	@if(@$schedule[0]['wed'])
	                      		   @php
							         $start_time = date('h ',strtotime($schedule[0]['wed_start_time']) ); 
							         $end_time = date('h A',strtotime($schedule[0]['wed_end_time']) );
							       @endphp
	                      		<p class="time">{{$start_time.' - '.$end_time}}</p>
                            @else
                          <p class="time"><span>-</span></p>
                      	@endif

	                      	</li>
	                      	<li>
	                      		<p class="day">Thur</p>
                      	@if(@$schedule[0]['thur'])
	                      		   @php
							         $start_time = date('h ',strtotime($schedule[0]['thur_start_time']) ); 
							         $end_time = date('h A',strtotime($schedule[0]['thur_end_time']) );
							       @endphp
	                      		<p class="time">{{$start_time.'-'.$end_time}}</p>
                          @else
                          <p class="time"><span>-</span></p>
                      	@endif

	                      	</li>
	                      	<li>
	                      		<p class="day">Fri</p>
                      	@if(@$schedule[0]['fri'])
	                      		   @php
							         $start_time = date('h ',strtotime($schedule[0]['fri_start_time']) ); 
							         $end_time = date('h A',strtotime($schedule[0]['fri_end_time']) );
							       @endphp
	                      		<p class="time">{{$start_time.' - '.$end_time}}</p>
                         @else
                          <p class="time"><span>-</span></p>
                      	@endif
	                      	</li>
	                      	<li>
	                      		<p class="day">Sat</p>
                      	@if(@$schedule[0]['sat'])
	                      		   @php
							         $start_time = date('h ',strtotime($schedule[0]['sat_start_time']) ); 
							         $end_time = date('h A',strtotime($schedule[0]['sat_end_time']) );
							       @endphp
	                      		<p class="time">{{$start_time.' - '.$end_time}}</p>
                          @else
                          <p class="time"><span>-</span></p>
                      	@endif
	                      	</li>

	                      	<li>
	                      		<p class="day">Sun</p>
                      	@if(@$schedule[0]['sun'])
	                      		   @php
							         $start_time = date('h ',strtotime($schedule[0]['sun_start_time']) ); 
							         $end_time = date('h A',strtotime($schedule[0]['sun_end_time']) );
							       @endphp
	                      		<p class="time">{{$start_time.' - '.$end_time}}</p>
                        @else
                          <p class="time"><span>-</span></p>

                      	@endif
	                      	</li>
                      </div>
                    </div>
                    
                    <!-- <div class="col-md-6 mk-pad-2"> -->
                        <!-- <div class="price row"> -->
                          @if(!$purchased_course)
                          @if( Auth::check() &&  @$logged_in_user->hasRole('supervisor'))
               
                <div class="free_trail">
                <h4 class="trial_price no_sub">You Can not Subscribe</h4>
                </div>
                          
                          @endif
                          @if( Auth::check() && $logged_in_user->hasRole('supervisor'))
                          @else
                                  	<div class="col-md-4 sub_btn">
	                                  	<h3>{{$course->sub_month_1}} Months</h3>
	                                  	<h5>{{$appCurrency["symbol"]}}{{$course->sub_price_1}}/month*</h5>
		                                <!-- <div class="col-md-4"> -->
		                                	<div class="sub_price">
			                                	@if(!auth()->check())
				                                	<a id="openLoginModal" class="sub_button" data-target="#myModal" href="#">Subscribe
				                                    </a>
				                                @else
			                                          
			                                            <form action="{{ route('cart.checkout') }}" method="POST">
			                                                @csrf                                     
			                                                <input type="hidden" name="liveclass_id" value="{{ $course->id }}">
			                                                <input type="hidden" name="sub_plan" value="1">
			                                                <input type="hidden" name="amount" value="{{ $course->sub_price_1}}">
			                                                <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Subscribe 
			                                                </button>
			                                            </form>
			                                          
			                                    @endif
		                                    <!-- </div> -->
		                                </div>
                        			</div>
	                                <div class="col-md-4 sub_btn">
	                                  <h3 class="popular_trial_wrapper">{{$course->sub_month_2}} Months</h3>
	                                  <span class="trial_popular_text">Popular</span>
	                                  <h5>{{$appCurrency["symbol"]}}{{$course->sub_price_2}}/month*</h5>
	                                  <div class="sub_price">
	                                  		@if(!auth()->check())
			                                	<a id="openLoginModal"class="sub_button" data-target="#myModal" href="#">Subscribe
			                                    </a>
			                                @else
	                                            <div class="sub_price">
	                                              <form action="{{ route('cart.checkout') }}" method="POST">
	                                                  @csrf                     
	                                                  <input type="hidden" name="liveclass_id" value="{{ $course->id }}">
	                                                  <input type="hidden" name="sub_plan" value="2">
	                                                  <input type="hidden" name="amount" value="{{ $course->sub_price_2}}">
	                                                  <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Subscribe 
	                                                  </button>
	                                              </form>
	                                            </div>
                                            @endif
	                                  </div>
	                                </div>
	                                <div class="col-md-4 sub_btn">
	                                  <h3>{{$course->sub_month_3}} Months</h3>
	                                  <h5>{{$appCurrency["symbol"]}}{{$course->sub_price_3}}/month*</h5>
	                                  <div class="sub_price">
	                                  	@if(!auth()->check())
			                                	<a id="openLoginModal"class="sub_button" data-target="#myModal" href="#">Subscribe
			                                    </a>
		                                @else
	                                      <div class="sub_price">
	                                        <form action="{{ route('cart.checkout') }}" method="POST">
	                                          
	                                            @csrf     
	                                            <input type="hidden" name="liveclass_id" value="{{ $course->id }}">
	                                            <input type="hidden" name="sub_plan" value="3">
	                                            <input type="hidden" name="amount" value="{{ $course->sub_price_3}}">
	                                            <button class="btn-block text-white  gradient-bg text-center text-uppercase sub_button bold-font" href="#">Subscribe 
	                                            </button>
	                                        </form>
	                                      </div>
                                      	@endif
	                                  </div>
	                                </div> 
                                  @endif
                          @elseif($total_purchase)
                            <p class="purchase_note">This Batch is Full</p>
                          @else
                          <div class="col-md-12">
                            <p class="purchase_note">Alreardy Subscribe</p>
                          </div>
                          		
                          @endif
                        <!-- </div> -->
                    <!-- </div> -->
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
						</svg>
          </a>
                    </div>
                    @endif
                    	
                    
                  </div>
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
           

            <div class="row mt100">
              <div class="col-md-12">
                    <h2>Other Live classes you may be interested in.</h2>
                </div>
              <div class="col-md-12">
                    <div class="row other_courses">
                        @foreach($othercourses->take(6) as $item)
                          <div class="card col-md-4"> 
                              <a href="{{ route('liveclass.show', [$item->slug]) }}">
                                  <div class="thumbnailBox">
                                      <div class="heart"><img src="{{asset('assets/images/heart.svg')}}"></div>
                                      <div class="thumbPic-wrapper" >
                                        <div class="thumbPic" style="background-image: url({{ asset('storage/uploads/'.$item->course_image) }})">
                                            <div class="videobutton trailer">
                                              <img src="{{asset('assets/images/cam.svg')}}">
                                              <input type="hidden" name="videolink" value="{{$item->videolink}}">
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
                                                  <span class="rev-am"> ({{count($item->reviews) }} reviews)</span>
                                                </div>
                                              <div class="review-price">
                                                  <div class="price-amount"> 
                                                    <div class="language">
                                                        <div class="lan" >
                                                          {{ $item->title }}  
                                                        </div>
                                                        
                                                        <div class="lang">
                                                          {{$item->language_used}}                                                        </div>
                                                        
                                                        <div class="price">
                                                          <div class="Currency" >
                                                          		@if($item->trial_price)
												              	{{ $appCurrency["symbol"].' '.$item->trial_price }}*
												              	@else
												              	Free*
												              	@endif
                                                          </div>
                                                          <button> Book trial lesson</button> 
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
                                                                <a href="{{route('liveclass.review.edit',['id'=>$item->id])}}"
                                                                    class="mr-2">@lang('labels.general.edit')</a>
                                                                <a href="{{route('liveclass.review.delete',['id'=>$item->id])}}"
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
                                                    $route = route('liveclass.review.update',['id'=>$review->id]);
                                                }else{
                                                    $route = route('liveclass.review',['course'=>$course->id]);
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


<!-- --------------- Trial Book Modal ------------------ -->


<div class="modal" id="trial_book">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
      	<div class="col-12 head">
      		<h4>Instant booking</h4>
      	</div>
      </div>

      <!-- Modal body -->
      <div class="modal-body booking">
      	<div class="label">
      		<span class="label_text">1. Select Time</span>
      		<span class="label_text">2. Make Payment</span>
  	 	</div>

        		<fieldset>
		    	<div class="row">
		    		<div class="container">
				    	<div class="col-sm-1 week {{date('l') == 'Monday'? 'disable' : ''}}" >
				    		<p class="day  "> Mon</p>
				    		@if(@$trail_schedule[0]['mon1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_mon_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Monday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
				    	<div class="col-sm-1 week {{date('l') == 'Tuesday'? 'disable' : ''}}" >
				    		<p class="day "> Tue </p>
				    		@if(@$trail_schedule[0]['tue1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_tue_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Tuesday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
				    	<div class="col-sm-1 week {{date('l') == 'Wednesday'? 'disable' : ''}}" >
				    		<p class="day"> Wed </p>
				    		@if(@$trail_schedule[0]['wed1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_wed_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Wednesday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
				    	<div class="col-sm-1 week {{date('l') == 'Thursday'? 'disable' : ''}}" >
				    		<p class="day"> Thu </p>
				    		@if(@$trail_schedule[0]['thur1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_thur_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Thursday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
				    	<div class="col-sm-1 week {{date('l') == 'Friday'? 'disable' : ''}}" >
				    		<p class="day"> Fri </p>
				    		@if(@$trail_schedule[0]['fri1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_fri_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Friday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
				    	<div class="col-sm-1 week {{date('l') == 'Saturday'? 'disable' : ''}}" >
				    		<p class="day"> Sat </p>
				    		@if(@$trail_schedule[0]['sat1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_sat_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Saturday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
				    	<div class="col-sm-1 week {{date('l') == 'Sunday'? 'disable' : ''}}" >
				    		<p class="day"> Sun </p>
				    		@if(@$trail_schedule[0]['sun1'])
			    				@php 
				    				$timings = explode(',',$trail_schedule[0]['trial_sun_start_time']);
				    			@endphp 
					    		@foreach($timings as $time)
					    			<li data-time="Sunday_{{ $time }}" class="select">{{ $time }}</li>
					    		@endforeach
				    		@endif
				    	</div>
		    		</div>
		    		
		    	</div>
		    </fieldset>
		    <div class="timezome_wrapper">
		    	<i class="far fa-clock timezone_color"></i>
                        All the timings listed are in India Standard Time
                        <span class="timezone_color">(UTC +5:30)</span>
             </div>
		    

    	<!-- <h3>Make a payment</h3> -->
		   <!--  <fieldset>
		    	@if(session()->has('selected_time'))
			        
			        @if($course->trial_price)
			              	@php   $amount =  $course->trial_price*100   @endphp
			              	
                             <div class="show_payment"></div>
		                 	<div class="razorpay_wrapper">
			                 	<div class="razorpay_uppersec">
			                 		<h4>Pay securely by Credit Card/Debit Card/NetBanking</h4>
			                 		<img src="{{ asset('assets/images/razorpay_logo.png') }}"> 
			                 	</div>
			                    <form action="{{route('trialconfirm')}}" method="POST" >
			                        <input type="hidden" name="sub_dur" value="{{ encrypt($course->sub_duration) }}">
			                        <script> 
			                        	$.ajax({
											    type: 'POST',
											    url: '{{ route('sel_time') }}',
											    data: { 
											        '_token': '{{csrf_token()}}', 
											        'data-key':"{{ config('services.razorpay.key') }}",
											        'data-amount' : "{{ $amount }}",
											        'data-buttontext' : 'Pay Now',
											        'data-name': "Samitya",
											    },
											    
											});
			                        </script>
			                      
			                        <input type="hidden" name="_token" value="{!!csrf_token()!!}"> 
			                    </form>
			                </div>
	              	@else
		              	<h4>This Trial Lesson is Free ,Press </h4>
	              	@endif
		                
	                
	            @else
	            <p>Please Select Time First</p>
                @endif
		    </fieldset> -->
      </div>

      <!-- Modal footer -->
      <!-- div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div> -->

    </div>
  </div>
</div>
<!-- --------------- /Trial Book Modal ------------------ -->
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
                                  <select class="form-control mb-0" name="amount" id="amount"> Select The Subscription
                                    <option value="0" disabled selected > Select The Subscription </option>
                                    <option value="{{  $course->sub_price_1 }}" data-sub_plan ="1">{{$appCurrency["symbol"]}} {{$course->sub_price_1}} ({{$course->sub_month_1}} months)</option>
                                    <option value="{{ $course->sub_price_2}}" data-sub_plan="2">{{$appCurrency["symbol"]}}{{$course->sub_price_2}} ({{$course->sub_month_2}} months)</option>
                                    <option value="{{ $course->sub_price_3}}" data-sub_plan="3">{{$appCurrency["symbol"]}}{{$course->sub_price_3}} ({{$course->sub_month_3}} months)</option>
                                    </select>
          <input type='hidden' id="name" name="sub_plan" value=""/>
                             <input type="hidden" name="liveclass_id" value="{{ $course->id }}">      
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

<!-- End of course details section
    ============================================= -->


@endsection

@push('after-scripts')
    <script>
        $(document).on('change', 'input[name="stars"]', function () {
            $('#rating').val($(this).val());
        })
                @if(isset($review))
        var rating = "{{$review->rating}}";
        $('input[value="' + rating + '"]').prop("checked", true);
        $('#rating').val(rating);
        $("#reviewmodal").modal("show");
        @endif
    </script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.js"></script>
    <script type="text/javascript">
    var time_select =0;
    	
     var form = $('.booking');


//     	form.steps({
//     headerTag: "h3",
//     bodyTag: "fieldset",
//     transitionEffect: "slideLeft",
//     autoFocus: true,

// });

$('li.select').click(function(){


	var  $selected_time = $(this).data('time') 
	$.ajax({
	    type: 'POST',
	    url: '{{ route('sel_time') }}',
	    data: { 
	        '_token': '{{csrf_token()}}', 
	        'time': $selected_time ,
	        'course': '{{ $course->id }}',
	    },
	    success: function(redirect){
	    	window.location.href = redirect;
	    }
	});

	 

	 

})

// 





    </script>
<!-- <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                        data-key="{{ config('services.razorpay.key') }}"
                                                        data-amount="100"
                                                        data-buttontext="Pay Now"
                                                        data-name="Samitya">
                                                </script>  -->




@endpush


<!-- ************************ -->





@push('after-scripts')
    <script>
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