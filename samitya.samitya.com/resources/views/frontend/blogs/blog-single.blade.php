@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($blog->meta_title) ? $blog->meta_title : app_name() )
@section('meta_description', $blog->meta_description)
@section('meta_keywords', $blog->meta_keywords)



@push('after-styles')

<style type="text/css">

.entry-meta li {
    padding-right: 23px;
    margin-right: 20px;
    list-style: none;
    display: inline-block;
    line-height: 18px;
    margin-top: 10px;
}
.entry-meta li:not(:last-child) {
    border-right: 1px solid #eee;
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
.date-meta {
    font-size: 72px;
    font-weight: 700;
    height: 82px;
    width: 110px;
    float: left;
    line-height: 72px;
    border-right: 1px solid #ccc;
    color: #3d2d48;
}
.entry-meta li p {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    display: inline-block;
    color: #7a7a7a;
}

.entry-meta li span {
    color: #ccc;
    font-size: 12px;
    font-weight: 700;
    display: block;
    line-height: 18px;
}
.share-social.ul-li {
    padding: 8px 0px;
}
.ul-li ul li {
    list-style: none;
    display: inline-block;
    width: 4%;
    margin-right: 15px;
    margin-left: 0px !important;
}
.side-bar-search {
    display: none;
}
.blog-details-content .author-comment {

     background-color: transparent; 
}
.blog-details-content .author-comment .author-img {
    height: 130px;
    width: 130px;
}
.author_image{
    border-radius: 100%;
    height: 100%;
}
.blog-details-content .author-comment .author-img {
    height: 130px;
    width: 130px;    
    border-radius: 0;
}
.blog-title-content.headline a{
        color: #3d2d48;
}
.section-title-2 h2 span {
    font-weight: inherit;
}
.side-bar-widget .widget-title span {
    font-weight: normal;
}
</style>

@endpush


@section('content')

    <!-- Start of breadcrumb section
    ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <!-- <h2 class="breadcrumb-head black bold">{{$blog->title}}</h2> -->
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of Blog single content
        ============================================= -->
    <section id="blog-detail" class="blog-details-section">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="blog-details-content">
                        <div class="post-content-details">
                            <!-- <h2>{{$blog->title}}</h2> -->
                            <!-- <div class="date-meta text-uppercase">
                                <span><i class="fas fa-calendar-alt"></i> {{$blog->created_at->format('d M Y')}}</span>
                                <span><i class="fas fa-user"></i> {{$blog->author->name}}</span>
                                <span><i class="fas fa-comment-dots"></i> {{$blog->comments->count()}}</span>
                                <span><i class="fas fa-tag"><a
                                                href="{{route('blogs.category',['category' => $blog->category->slug])}}"> {{$blog->category->name}}</a></i></span>
                            </div> -->
                            <div class="entry-contain">
                                <h2 class="entry-title"> 
                                    <a href="{{route('blogs.index',['slug'=> $blog->slug.'-'.$blog->id])}}">{{$blog->title}} </a>
                                </h2> 
                                <ul class="entry-meta">
                                    <li class="author">
                                        <span class="desc_title">Posted by</span>
                                        <p class="author author_name ">{{$blog->author->name}}</p>               
                                    </li>
                                    <li class="entry-category">
                                        <span class="desc_title">Categories</span> 
                                        <a href="{{route('blogs.category',['category' => $blog->category->slug])}}"> <p>{{$blog->category->name}}</p></a>                
                                    </li>
                                    <li class="entry-category">
                                        <span class="desc_title">Date</span> 
                                         <p>{{$blog->created_at->format('d M Y')}}</p>                
                                    </li>
                                    <li class="comment-total">
                                        <span class="desc_title">Comments</span>
                                        <p> {{$blog->comments->count()}} </p>                    
                                    </li>

                                </ul>
                            </div>
                            @if($blog->image != "")

                                <div class="blog-detail-thumbnile mb35">
                                    <img src="{{asset('storage/uploads/'.$blog->image)}}" alt="">
                                </div>
                            @endif

                            

                            
                            <p>
                                {!! $blog->content !!}
                            </p>


                        </div>
                        <div class="blog-share-tag">
                            <div class="share-text ">
                                Share This Blog
                            </div>

                            <div class="share-social ul-li ">
                                <ul>
                                    <li>
                                        <a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u={{url()->current()}}">
                                            <!-- <i class="fab fa-facebook-f"></i> -->
                                            <img class="share_icon"  src="{{asset('assets/images/facebook-icon.png')}}" >
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="http://twitter.com/share?url={{url()->current()}}&text={{$blog->title}}">
                                            <!-- <i class="fab fa-twitter"></i> -->
                                            <img class="share_icon" src="{{asset('assets/images/twiter-icon.png')}}" >
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://api.whatsapp.com/send?phone=&text={{url()->current()}}">
                                            <!-- <i class="fab fa-whatsapp"></i> -->
                                            <img class="share_icon" src="{{asset('assets/images/whatsapp-icon.png')}}" >
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="author-comment d-inline-block p-3   h-100 d-table">
                            <div class="author-img float-none">
                                <img class="author_image" src="{{$blog->author->picture}}" alt="">
                            </div>
                            <span class="mt-2  d-table-cell align-middle"> <b>{{$blog->author->name}}</b></span>
                        </div>

                        <div class="next-prev-post">
                            @if($previous != "")
                                <div class="next-post-item float-left">
                                    <a href="{{route('blogs.index',['slug'=>$previous->slug.'-'.$previous->id ])}}"><i
                                                class="fas fa-arrow-circle-left"></i>Previous Post</a>
                                </div>
                            @endif

                            @if($next != "")
                                <div class="next-post-item float-right">
                                    <a href="{{route('blogs.index',['slug'=>$next->slug.'-'.$next->id ])}}">Next Post<i
                                                class="fas fa-arrow-circle-right"></i></a>
                                </div>
                                @endif

                        </div>
                    </div>

                    <div class="blog-recent-post about-teacher-2">
                        <div class="section-title-2  headline text-left">
                            <!-- <h2> @lang('labels.frontend.blog.related_news')</h2> -->
                            <h2> Related Blog</h2>
                        </div>
                        @if(count($related_news) > 0)
                            <div class="recent-post-item">
                                <div class="row">
                                    @foreach($related_news as $item)
                                        <div class="col-md-6">
                                            <div class="blog-post-img-content">
                                                <div class="blog-img-date relative-position">
                                                    <div class="blog-thumnile" @if($item->image != "") style="background-image: url({{asset('storage/uploads/'.$item->image)}})" @endif></div>

                                                    <div class="course-price text-center gradient-bg">
                                                        <span>{{$item->created_at->format('d M Y')}}</span>
                                                    </div>
                                                </div>
                                                <div class="blog-title-content headline">
                                                    <h3>
                                                        <a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id ])}}">{{$item->title}}</a>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="blog-comment-area ul-li about-teacher-2">
                        <div class="reply-comment-box">
                            <div class="section-title-2  headline text-left">
                                <h2> @lang('labels.frontend.blog.post_comments')</h2>
                            </div>

                            @if(auth()->check())
                                <div class="teacher-faq-form">
                                    <form method="POST" action="{{route('blogs.comment',['id'=>$blog->id])}}"
                                          data-lead="Residential">
                                        @csrf
                                        <div class="form-group">
                                            <label for="comment"> @lang('labels.frontend.blog.write_a_comment')</label>
                                            <textarea name="comment" required class="mb-0" id="comment" rows="2"
                                                      cols="20"></textarea>
                                            <span class="help-block text-danger">{{ $errors->first('comment', ':message') }}</span>
                                        </div>

                                        <div class="nws-button text-center  gradient-bg text-uppercase">
                                            <button type="submit" value="Submit"> @lang('labels.frontend.blog.add_comment')</button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <a id="openLoginModal" class="btn nws-button gradient-bg text-white"
                                   data-target="#myModal"> You must be logged in to post a comment.</a>
                            @endif
                        </div>
                        @if($blog->comments->count() > 0)

                        <ul class="comment-list my-5">
                                @foreach($blog->comments as $item)
                                <li class="d-block">
                                    <div class="comment-avater">
                                        <img src="{{$item->user->picture}}" alt="">
                                    </div>

                                    <div class="author-name-rate">
                                        <div class="author-name float-left">
                                            @lang('labels.frontend.blog.by'): <span>{{$item->name}}</span>
                                        </div>

                                        <div class="time-comment float-right">{{$item->created_at->diffforhumans()}}</div><br>
                                        @if($item->user_id == auth()->user()->id)
                                        <div class="time-comment float-right">

                                            <a class="text-danger font-weight-bolf" href="{{route('blogs.comment.delete',['id'=>$item->id])}}"> @lang('labels.general.delete')</a>

                                        </div>
                                        @endif
                                    </div>
                                    <div class="author-designation-comment">
                                        <p>{{$item->comment}}</p>
                                    </div>
                                </li>
                                @endforeach


                        </ul>
                        @else
                            <p class="my-5">@lang('labels.frontend.blog.no_comments_yet')</p>
                        @endif



                    </div>
                </div>
                @include('frontend.blogs.partials.sidebar')
            </div>
        </div>
    </section>
    <!-- End of Blog single content
        ============================================= -->


@endsection