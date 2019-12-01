
<!-- Start popular course
       ============================================= -->
@if(count($popular_courses) > 0)
 <div class="container">

    <section id="popular-course" class=" teacher-thumbnail-section popular-course-section {{isset($class) ? $class : ''}}">
                   <div  class="card-columns row">
                @foreach($popular_courses as $item)
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
                                        <!-- <ul> -->
                                            @for($r=1; $r<=round($item->reviews->avg('rating')); $r++)
                                                <img src="{{asset('assets/images/rating-star.png')}}">
                                            @endfor
                                            @for($r=round($item->reviews->avg('rating'))+1 ; $r<=5; $r++)
                                                <img src="{{asset('assets/images/blank-star.png')}}">
                                            @endfor
                                        <!-- </ul> -->
                                    </span>
                                    <!--  / Rating By theme -->
                                    <!-- <span class="star"><img src="../assets/images/filled-star.png"></span> -->
                                    <span class="rev-am">({{count($item->reviews) }} reviews)</span>
                                  </div>
                                <div class="review-price">
                                    <div class="price-amount"> <div class="language">
                                    <div class="lan" >{{ $item->title }}  </div>
                                    <div class="hrs">{{ $item->display_duration }}</div>
                                    
                                    <div class="price">
                                      @if($item->free == 0)
                                      <div class="Currency" >{{$appCurrency['symbol'].$item->sub_price_1 }}</div>
                                      @else
                                      <div class="Currency" >Free</div>
                                      @endif
                                      <button> Subscribe</button> </div>
                                  </div></div>
                                    
                                  </div>
                                </div>
                        </div>
                    </div>
                    </a>
                    </div>
                    <!-- /item -->
                @endforeach
            </div>
            <div class="samitya-button mt25">
              <a href="/courses">View All</a>
          </div>
                </section>
        </div>


@endif
    <!-- End popular course
        ============================================= -->