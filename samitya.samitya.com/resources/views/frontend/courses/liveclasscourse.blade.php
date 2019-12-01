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
          <div class="col-md-8 banner-section text-center " style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')">
            <img src="{{asset('assets/images/videoicon.png')}}">
          </div>
          @endif
          

           <div class="col-md-4 live_class_details">
             <div>
              <h4>Free*</h4>
              <button class="book_btn">Book trial lesson</button>
              <p>* For one trial lesson</p>
             </div>
             <div>
               <h4>Course detail</h4>
               <p>{{$course->description}}</p>
             </div>

             <div>
               <h4>First session</h4>
               <p>
                15 July 2019,  12:30pm
                Duration : 1Hr.
                <br>
                Registrations will be open till 15th July, 10:00 AM only
              </p>
             </div>

             <div>
               <h4>Requirement</h4>
               <p>Flute, High speed internet, Youtube account.</p>
             </div>

             <div>
               <p>Note : Live class will be conducted via youtube. Please make sure you have an active youtube account.</p>
             </div>
           </div>
          

         </div>
      </div>
    </section>
    <div class="container coursecontainer">
        <div class="row">
            <div class="col-md-8 mk-bor-2">
                <div class="row">
                    <div class="col-md-8">
                          <h2>{{$course->title}}</h2>
                            <h4>
                            @if($course->language_used)
                            Course language-{{$course->language_used}} 
                            @endif
                            @if($course->display_duration)
                            <span class="hrs lock">
                              {{$course->display_duration}}
                            </span>
                            @endif
                            </h4>
                          <img src="{{ asset('storage/'.$teacher->avatar_location) }}" class="mk-img-radi">
                          <h3 class="mk-h3">
                            {{$teacher->full_name}}
                          </h3>
                          @if($teacher->description)
                          <h5>
                            {{$teacher->full_name}} Bio
                          </h5>   
                          @endif          
                    </div>
                    <div class="col-md-4 mk-right-left">
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
            </div>
            <div class="col-md-4 mk-bor-2">
              @if($course->downloadable_files)
              <p class="mk-dc">Supporting document 
                <div class="doc">
                    <a class="doc" href="{{route('admin.invoice.downloadfiles',['course'=>$course->downloadable_files])}}">
                    </a>
                </div>
              </p>
              @endif
                  <div class="row mk-pad">
                    <div class="col-md-6 mk-pad-2">
                      <h2>{{$appCurrency["symbol"]}}{{$course->price}}*</h2>
                    </div>
                    <div class="col-md-6">
                        <div class="price">
                          @if(!$purchased_course)
                              @if(!auth()->check())
                                    <a id="openLoginModal"
                                    class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font"
                                    data-target="#myModal" href="#">Subscribe
                                    </a>
                                  @elseif(auth()->check() )
                                    <form action="{{ route('cart.checkout') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                        <input type="hidden" name="amount" value="{{ $course->price}}"/>
                                        <button class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font"
                                                href="#">Subscribe 
                                        </button>
                                    </form>
                              @endif
                          @else
                              @if($continue_course)
                                    <a href="{{route('lessons.show',['id' => $course->id,'slug'=>$continue_course->model->slug])}}"
                                           class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font">
                                            @lang('labels.frontend.course.continue_course')
                                            <i class="fa fa-arrow-right"></i>
                                    </a>
                              @endif
                          @endif
                        </div>
                    </div>
                  </div>
                  <div class=" mk-no-line">
                  @if($course->sub_duration)
                    <p>*This price is for {{ $course->sub_duration }} months access,</p>
                    <p>after {{ $course->sub_duration }} months you need to subscribe again.</p>
                  @endif
                  </div>
                  <div class=" mk-no-line">
                  <h4>Share this course</h4>
                  <!-- <img src="images/social-icon.jpg" class="mk-social"> -->
                  <ul class="mk-social-ul">
                      <li><img src="{{asset('assets/images/social-icon-1.jpg')}}" class="mk-social"></li>
                      <li><img src="{{asset('assets/images/social-icon-2.jpg')}}" class="mk-social"></li>
                      <li><img src="{{asset('assets/images/social-icon-3.jpg')}}" class="mk-social"></li>
                      <li><img src="{{asset('assets/images/social-icon-4.jpg')}}" class="mk-social"></li>
                      <li><img src="{{asset('assets/images/social-icon-5.jpg')}}" class="mk-social"></li>
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
                        @foreach($livecourses->take(6) as $item)
                          <div class="card col-md-4"> 
                              <a href="{{ route('courses.show', [$item->slug]) }}">
                                  <div class="thumbnailBox">
                                      <div class="heart"><img src="../assets/images/heart.svg"></div>
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
                                                        <div class="lang">
                                                          {{$item->language_used}}                                                        </div>
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
                <div class="taught_sec row">
                  <div class="col-sm-12 img">
                    <img src="{{asset('assets/images/Skype-Logo.png')}}">
                    <p>Taught On</p> 
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
        @endif
    </script>
@endpush