
<!-- Start Live course
       ============================================= -->
@if(count($live_courses) > 0)

<section id='' class="teacher-thumbnail-section subscribe-block">  
      <div class="container">
        <div class="teacher-thumbnail-container">
          <div class="col-md-12">
            <div class="liveclass_heading_wrapper">
              <div class="text">
                Live classes
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" id="Layer_1" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50" xml:space="preserve">
                  <g>
                    <path d="M33,22c0-4.411-3.589-8-8-8s-8,3.589-8,8s3.589,8,8,8S33,26.411,33,22z M19,22c0-3.309,2.691-6,6-6s6,2.691,6,6   s-2.691,6-6,6S19,25.309,19,22z"></path>
                    <path d="M37,22c0-6.617-5.383-12-12-12c-6.617,0-12,5.383-12,12c0,6.617,5.383,12,12,12C31.617,34,37,28.617,37,22z M15,22   c0-5.514,4.486-10,10-10c5.514,0,10,4.486,10,10c0,5.514-4.486,10-10,10C19.486,32,15,27.514,15,22z"></path>
                    <path d="M36.908,39.278C42.394,35.485,46,29.159,46,22c0-11.58-9.421-21-21-21C13.42,1,4,10.42,4,22   c0,7.159,3.606,13.485,9.092,17.278C9.802,40.06,6.051,41.499,6.051,44c0,3.905,9.533,5.948,18.949,5.948S43.948,47.905,43.948,44   C43.948,41.497,40.186,40.058,36.908,39.278z M6,22C6,11.523,14.523,3,25,3s19,8.523,19,19s-8.523,19-19,19S6,32.477,6,22z    M25,48.052c-11.195,0-17.051-2.638-17.051-4.052c0-0.761,2.065-2.314,7.572-3.286C18.373,42.164,21.588,43,25,43   c3.413,0,6.628-0.836,9.479-2.287c5.509,0.973,7.572,2.528,7.572,3.287C42.052,45.414,36.195,48.052,25,48.052z"></path>
                  </g>
                </svg>
              </div>
            </div>         
          </div>
          <div class="card-columns row live_index other_courses">
            

             @foreach($live_courses as $item)


                      <div class="card col-md-4"> 
                          <a href="{{ route('liveclass.show', [$item->slug]) }}">
                              <div class="thumbnailBox">
                                  <div class="heart"><img src="../assets/images/heart.svg"></div>
                                  <div class="thumbPic-wrapper" >
                                    <div class="thumbPic" style="background-image: url({{ asset('storage/uploads/'.$item->course_image) }})">
                                        <div class="videobutton trailer">
                                          <img src="{{asset('assets/images/cam.svg')}}">
                                          <!-- <img src="{{asset('assets/images/video_camera_filled-svg.svg')}}"> -->
                                          <input type="hidden" name="videolink" value="{{$item->videolink}}">
                                        </div>
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
                                                      <button>Book trial lesson</button> 
                                                      <p class="trial_note">* For one trial lesson</p>
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

          <div class="samitya-button mt25">
            <a href="{{route('liveclass.all')}}">More Live Classes</a>
          </div>
       
        </div>
      </div>
    </section>

@endif
<!-- End Live course
        ============================================= -->