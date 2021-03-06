@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )



@section('content')
	<div class="container">
		<div class="row">
			<div class="live_page_wrapper">
				<div class="text">
					Live classes
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" id="Layer_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50"  xml:space="preserve">
						<g>
							<path d="M33,22c0-4.411-3.589-8-8-8s-8,3.589-8,8s3.589,8,8,8S33,26.411,33,22z M19,22c0-3.309,2.691-6,6-6s6,2.691,6,6   s-2.691,6-6,6S19,25.309,19,22z"/>
							<path d="M37,22c0-6.617-5.383-12-12-12c-6.617,0-12,5.383-12,12c0,6.617,5.383,12,12,12C31.617,34,37,28.617,37,22z M15,22   c0-5.514,4.486-10,10-10c5.514,0,10,4.486,10,10c0,5.514-4.486,10-10,10C19.486,32,15,27.514,15,22z"/>
							<path d="M36.908,39.278C42.394,35.485,46,29.159,46,22c0-11.58-9.421-21-21-21C13.42,1,4,10.42,4,22   c0,7.159,3.606,13.485,9.092,17.278C9.802,40.06,6.051,41.499,6.051,44c0,3.905,9.533,5.948,18.949,5.948S43.948,47.905,43.948,44   C43.948,41.497,40.186,40.058,36.908,39.278z M6,22C6,11.523,14.523,3,25,3s19,8.523,19,19s-8.523,19-19,19S6,32.477,6,22z    M25,48.052c-11.195,0-17.051-2.638-17.051-4.052c0-0.761,2.065-2.314,7.572-3.286C18.373,42.164,21.588,43,25,43   c3.413,0,6.628-0.836,9.479-2.287c5.509,0.973,7.572,2.528,7.572,3.287C42.052,45.414,36.195,48.052,25,48.052z"/>
						</g>
					</svg>
					<p class="small_text">Coming soon</p>
				</div>

			</div>
		</div>
	</div>
	

	<section class="offer-section">
    
      <div class="container">
        <div class="row mt100">
          <div class="col-md-4">
            <div class="feature">
              <div class="feature-icon">
                <img src="{{ asset('/assets/images/instantchat.svg') }}"></div>
              <h3>Chat instantly with  <br>teacher</h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature">
              <div class="feature-icon chat-icon"><img src="{{ asset('/assets/images/learndetail.svg') }}"></div>
              <h3>Learn in detail</h3>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature">
               <div class="feature-icon"><img src="{{ asset('/assets/images/playback.svg') }}"></div>
              <h3>Playback and practice</h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id='live_clases' class="teacher-thumbnail-section subscribe-block">  
      <div class="container">
        <div class="teacher-thumbnail-container">
          <div class="card-columns row">

           @foreach($courses as $item)
                          <div class="card col-md-4"> 
                              <a href="{{ route('courses.show', [$item->slug]) }}">
                                  <div class="thumbnailBox">
                                      <div class="heart"><img src="../assets/images/heart.svg"></div>
                                      <div class="thumbPic-wrapper" >
                                        <div class="thumbPic" style="background-image: url({{ asset('storage/uploads/'.$item->course_image) }})">
                                            <div class="videobutton">
                                              <img src="../assets/images/play-icon.png">
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
                                                          {{$course->language_used}}                                                        </div>
                                                        @endif
                                                        <div class="price">
                                                          <div class="Currency" >
                                                            {{$appCurrency['symbol'].$item->price }}
                                                          </div>
                                                          <button> Show interest</button> 
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


            <div class="clearfix"></div>
          </div>
       
        </div>

        <!-- <div class="live_faq">
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
        </div> -->

        <div class="taught_sec row">
          <div class="col-sm-12 img">
            <img src="{{asset('assets/images/Skype-Logo.png')}}">
            <p>Taught On</p> 
          </div>
            
              
              
          </div>
        </div>


      </div>
	</section>
	




@endsection