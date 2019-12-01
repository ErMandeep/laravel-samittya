<!-- Start of footer area
    ============================================= -->
<footer>
      <div class="footer-section">
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <ul class="footer-links">
                <li><a href="{{url('/category/dance/courses')}}">Online Dance classes</a></li>
                <li><a href="{{url('/category/drum/courses')}}">Online Drums classes</a></li>
                <li><a href="{{url('/category/flute/courses')}}">Online Flute classes</a></li>
                <li><a href=" {{url('/category/vocal/courses')}}">Online Hindustani vocal classes</a></li>
                <li><a href="{{url('/category/guitar/courses')}}">Online Guitar classes</a></li>
                <li><a href="{{url('/category/tabla/courses')}}">Online Tabla classes</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <ul class="footer-links">
                <li><a href="{{url('/terms-conditions')}}">Privacy policy</a></li>
                <li><a href="{{ route('contact') }}">Contact us</a></li>
                <li><a href="{{ route('blogs.index') }}">Blog</a></li>
                <li><a href="{{url('/forums')}}">Community</a></li>
                <li><a href="{{ route('gallary.all') }}">Gallery</a></li>
                <li><a href="{{ route('news.all') }}">News</a></li>
                <li><a href="{{ route('offline.login') }}">Offline Login</a></li>
              </ul>
            </div>
            <div class="col-md-4">
              <ul class="footer-social">
                <li><a href="javascript:void();"><img src="{{asset('assets/images/facebook2.svg')}}"></a></li>
                <li><a href="javascript:void();"><img src="{{asset('assets/images/twitter2.svg')}}"></a></li>
                <li><a href="javascript:void();"><img src="{{asset('assets/images/google2.svg')}}"></a></li>
                <li><a href="javascript:void();"><img src="{{asset('assets/images/instagram2.svg')}}"></a></li>
                <li><a href="javascript:void();"><img src="{{asset('assets/images/youtube2.svg')}}"></a></li>
              </ul>




              <ul class="footer-social">
                            <div class="subscribe-form ml-0 ">
                            <h2 class="widget-title"></h2>

                            <div class="subs-form relative-position">
                            <form action="{{route('subscribe')}}" method="post">
                                        @csrf
                                        <input class="email" required name="subs_email" type="email" placeholder="@lang('labels.frontend.layouts.partials.email_address')." >
                                        <div class="nws-button text-center  gradient-bg text-uppercase">
                                            <button type="submit" value="Submit">Subcribe</button>
                                        </div>
                                        @if($errors->has('email'))
                                            <p class="text-danger text-left">{{$errors->first('email')}}</p>
                                        @endif
                                    </form>

                            </div>
                            </div>
                        </div>

          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <div class="copyright">&copy 2011–2019 SAMITYA, INC</div>
            </div>
          </div>
        </div>
      </div>
    </footer>


<!-- End of footer area
============================================= -->