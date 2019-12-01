@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" /> -->


    <!-- <link rel="stylesheet" href="https://rawgit.com/LeshikJanz/libraries/master/Bootstrap/baguetteBox.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/justifiedGallery/3.7.0/css/justifiedGallery.css" integrity="sha256-ZKOGvp7YVwX26g2d0ooDvbSBQSEiIi4Bd9FuK+12Zk0=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.4.4/css/swipebox.css"  />



    <style>
      
body {
    /*background-color: #434c50;*/
    min-height: 100vh;
    font: normal 16px sans-serif;
    padding: 40px 0;
}

.container.gallery-container {
    background-color: #fff;
    color: #35373a;
    min-height: 100vh;
    /*padding: 30px 50px;*/
}

.gallery-container h1 {
    text-align: center;
    margin-top: 50px;
    font-family: 'Droid Sans', sans-serif;
    font-weight: bold;
}

.gallery-container p.page-description {
    text-align: center;
    /*margin: 25px auto;*/
    margin-left: -15px;
    font-size: 18px;
    font-weight: bold;
    color: #000;
}

.tz-gallery {
    padding: 40px;
}

/* Override bootstrap column paddings */
.tz-gallery .row > div {
    /*padding: 150px;*/
    padding-bottom: 280px;
}

.tz-gallery .lightbox img {
    width: 100%;
    border-radius: 0;
    position: relative;
}

.tz-gallery .lightbox:before {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -13px;
    margin-left: -13px;
    opacity: 0;
    color: #fff;
    font-size: 26px;
    font-family: 'Glyphicons Halflings';
    content: '\e003';
    pointer-events: none;
    z-index: 9000;
    transition: 0.4s;
}


.tz-gallery .lightbox:after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    background-color: rgba(46, 132, 206, 0.7);
    content: '';
    transition: 0.4s;
}

.tz-gallery .lightbox:hover:after,
.tz-gallery .lightbox:hover:before {
    opacity: 1;
}

.baguetteBox-button {
    background-color: transparent !important;
}
.course-page-section {
     padding: 0; 
    margin-bottom: 65px;
}
.boxx{
    margin: 30px;
    border: 1px solid #eee;
    box-shadow: 0px 0px 20px #eee;
    padding: 28px 20px;
}

@media(max-width: 768px) {
    body {
        padding: 0;
    }
}
    </style>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style" style="display: none;">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">
                        <span>@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif </span>
                    </h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->
<!-- *********************************************************************************************************************** -->


<!-- *********************************************************************************************************************** -->

    <!-- Start of course section
        ============================================= -->







    <section id="course-page" class="course-page-section">
   <!--      <div class="container">
            <div class="row">
                <div class="col-md-12">
                   
            <div class="row">
                
        @if(count($pages) > 0)
                @foreach($pages as $media)
                <div>
               
                @foreach($media->new_media as $m)
                    <div class="col-3 form-group">
                        <a href="{{ asset('storage/uploads/'.$m->name) }}" target="_blank"><img
                                    src="{{ asset('storage/uploads/'.$m->name) }}" style="width: 240px;" ></a>
                                     <a href="#" data-media-id="{{$m->id}}"
                                           class="btn btn-xs btn-danger delete remove-file">@lang('labels.backend.lessons.remove')</a>
                                    </p>
                    </div>
                @endforeach
                  
                </div>
        
                    @endforeach
                @endif
            </div>

                </div>

 
            </div>
        </div> -->
<!-- ****************************************** -->

   
    
            


<!-- ************************************ -->

     @foreach($pages as $media)
     <div class="boxx">
    <h3 class="page-description text-center">{{$media->title}}</h3>
    <p class="page-description text-center">{{$media->content}}</p> 
<div class="swipeboxExample" id="swipeboxExample" style="line-height: 50px;">

     @foreach($media->new_media as $m)


    <a href="{{ asset('storage/uploads/'.$m->name) }}" class="swipeboxExampleImg">
        <img alt="" src="{{ asset('storage/uploads/'.$m->name) }}" />
    </a>



      @endforeach
</div>
</div>

      @endforeach





<!-- ************************************ -->








    </section>
    <!-- End of course section
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
    <!-- @include('frontend.layouts.partials.browse_courses') -->
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
  <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.swipebox/1.4.4/js/jquery.swipebox.js" ></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/justifiedGallery/3.7.0/js/jquery.justifiedGallery.js" integrity="sha256-aICGCAHM7y8hYF3afFvb588+WLndzls+NZ7IqmVBTdE=" crossorigin="anonymous"></script>

  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script> -->
<script>
    // baguetteBox.run('.tz-gallery');
</script>
     <script>


$('.swipeboxExampleImg').swipebox();

$('.swipeboxExample').justifiedGallery({
    lastRow : 'nojustify', 
    rowHeight : 305, 
    rel : 'gallery2',
    margins : 1
}).on('jg.complete', function () {
    $('.swipeboxExampleImg').swipebox();
});
       





        $(document).ready(function () {



});
</script>








@endpush


   