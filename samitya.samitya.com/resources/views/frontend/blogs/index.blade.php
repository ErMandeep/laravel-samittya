@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.blog.title').' | '.app_name())

@push('after-styles')
    <style>
        .couse-pagination li.active {
            color: #333333!important;
            font-weight: 700;
        }
        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color:white;
            border:none;

        }
        ul.pagination{
            display: inline;
            text-align: center;
        }
        .cat-item.active{
            background: black;
            color: white;
            font-weight: bold;
        }


ul.entry-meta {
    padding-left: 0;
}
.entry-title a {
    color: #3d2d48;
    text-transform: capitalize;
    font-size: 30px;
        font-weight: 700;
}
body {
    /*background-color: #434c50;*/
    min-height: 100vh;
    font: normal 16px sans-serif;
}
.blog-item-post {
    padding: 0;
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

.pagedes{
    margin-top: 25px;
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
.entry-header {
    padding-bottom: 0;
    margin-bottom: 23px;
    overflow: hidden;
    margin-top: 30px;
}
.entry-header .date-meta {
    font-size: 72px;
    font-weight: 700;
    height: 82px;
    width: 110px;
    float: left;
    line-height: 72px;
    border-right: 1px solid #ccc;
    color: #3d2d48;
}
.entry-header .entry-contain {
    position: relative;
    padding-left: 30px;
    width: calc(100% - 110px);
    width: -webkit-calc(100% - 110px);
    width: -moz-calc(100% - 110px);
    float: right;
}


.entry-header .date-meta i {
    font-size: 15px;
    text-transform: uppercase;
    font-style: normal;
    font-weight: 700;
    display: block;
    line-height: 20px;
    margin-top: 3px;
}
.boxx{
    margin: 30px;
    /*border: 1px solid #eee;*/
    /*box-shadow: 0px 0px 20px #eee;*/
    padding: 10px;
}

.entry-meta li p {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    display: inline-block;
    color: #7a7a7a;
}


@media(max-width: 768px) {
    body {
        padding: 0;
    }
}

.pmpro-has-access .entry-content ul {
    list-style: inherit;
}
 .entry-header .entry-meta {
    margin: 0;
}

 .entry-header .entry-meta li {
    border-right: 1px solid #eee;
    padding-right: 23px;
    margin-right: 20px;
    list-style: none;
    display: inline-block;
    line-height: 18px;
    margin-top: 10px;
}
 .entry-header .entry-meta li span {
    color: #ccc;
    font-size: 12px;
    font-weight: 700;
    display: block;
    line-height: 18px;
}

 .entry-header .entry-meta li a,  .entry-header .entry-meta li span.value {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    display: inline-block;
    color: #7a7a7a;
}

 .entry-header .entry-meta li {
    border-right: 1px solid #eee;
    padding-right: 23px;
    margin-right: 20px;
    list-style: none;
    display: inline-block;
    line-height: 18px;
    margin-top: 10px;
}

 .entry-header .entry-meta li span {
    color: #ccc;
    font-size: 12px;
    font-weight: 700;
    display: block;
    line-height: 18px;
}
 .entry-header .entry-meta li a,  .entry-header .entry-meta li span.value {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    display: inline-block;
    color: #7a7a7a;
}

 .entry-header .entry-meta li:last-child {
    padding-right: 0;
    border-right: 0;
    margin-right: 0;
}

 .entry-header .entry-meta li span {
    color: #ccc;
    font-size: 12px;
    font-weight: 700;
    display: block;
    line-height: 18px;
}


 .readmore {
    text-transform: uppercase;
    font-size: 13px;
    font-weight: 700;
    margin-top: 23px;
}

.readmore a {
    display: inline-block;
    line-height: 30px;
    padding: 5px 25px;
    border: 0;
    color: #fff;
    background-color: #3d2d48;
}
.couse-pagination.text-center.ul-li {
    padding: 20px 0px;
}

    </style>
@endpush
@section('content')

    <!-- Start of breadcrumb section
    ============================================= -->
    <!-- <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">@if(isset($category)){{$category->name}} @elseif(isset($tag)) {{$tag->name}} @endif  <span>@lang('labels.frontend.blog.title')</span></h2>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End of breadcrumb section
        ============================================= -->




    <!-- Start of blog content
        ============================================= -->
<section id="blog-item" class="blog-item-post">
        <!-- <div class="container"> -->
            <!-- <div class="blog-content-details"> -->
                <!-- <div class="row"> -->
                    <!-- <div class="col-md-9"> -->
                        <!-- <div class="blog-post-content"> -->
                          <!--   <div class="short-filter-tab">
                                <div class="tab-button blog-button ul-li text-center float-left">
                                    <ul class="product-tab">
                                        <li class="active" rel="tab1"><i class="fas fa-th"></i></li>
                                        <li rel="tab2"><i class="fas fa-list"></i></li>
                                    </ul>
                                </div>
                            </div> -->
        <div class="row">
            <div class="container">
                @if(count($blogs) > 0)
                @foreach($blogs as $media)
                    <div class="boxx">
                        @if($media->image)
                            <a href="{{route('blogs.index',['slug'=> $media->slug.'-'.$media->id])}}"> <img class="blog-thumnile" src="{{asset('storage/uploads/'.$media->image)}}"> </a>
                        @endif
                        <div class="entry-header">
                            <div class="date-meta">
                                {{ date("j", strtotime($media->created_at)) }}<i> {{ date("F", strtotime($media->created_at)) }}</i>                      
                            </div>
                            <div class="entry-contain">
                                <h2 class="entry-title"> 
                                    <a href="{{route('blogs.index',['slug'=> $media->slug.'-'.$media->id])}}">{{$media->title}} </a>
                                </h2> 
                                <ul class="entry-meta">
                                    <li class="author">
                                        <span class="desc_title">Posted by</span>
                                        <p class="author author_name ">{{$media->author->name}}</p>               
                                    </li>
                                    <li class="entry-category">
                                        <span class="desc_title">Categories</span> 
                                        <a href="{{route('blogs.category',['category' => $media->category->slug])}}"> <p>{{$media->category->name}}</p></a>                
                                    </li>
                                    <li class="comment-total">
                                        <span class="desc_title">Comments</span>
                                        <p> {{$media->comments->count()}} </p>                    
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="readmore">
                            <a href="{{route('blogs.index',['slug'=> $media->slug.'-'.$media->id])}}">Read More</a>
                        </div>
                    </div>
                @endforeach
                <div class="couse-pagination text-center ul-li">
                    {{ $blogs->links() }}
                </div>
            @else 
            <div>Nothing Found</div>
            @endif
            </div>
        </div>
            
            
                        <!-- </div> -->
                    <!-- </div> -->
                   <!-- @include('frontend.blogs.partials.sidebar') -->
                <!-- </div> -->
            <!-- </div> -->
        <!-- </div> -->
</section>
    <!-- End of blog content
        ============================================= -->

@endsection