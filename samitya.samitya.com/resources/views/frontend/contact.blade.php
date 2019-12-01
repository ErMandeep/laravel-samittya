@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', 'Contact | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')

@push('after-styles')
    <style>
        .my-alert{
            position: absolute;
            z-index: 10;
            left: 0;
            right: 0;
            top: 25%;
            width: 50%;
            margin: auto;
            display: inline-block;
        }
        .vvcapcha{
        	margin-left: 15px;
        }
 		.dataok{

	text-align: center;
    /* font-size: 25px; */
    color: #3d2d48;
    margin-top: -60px;
    background: #28a745;
    color: #fff;



 		}

    </style>
@endpush

@section('content')
    @php
        $footer_data = json_decode(config('footer_data'));
    @endphp
    @if(session()->has('alert'))
        <div class="alert alert-light alert-dismissible fade my-alert show dataok">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{session('alert')}}</strong>
        </div>
    @endif


	@php
        $contact_data = contact_data(config('contact_data'));
    @endphp


    <!-- Start of breadcrumb section
        ============================================= -->
    <!-- <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">{{env('APP_NAME')}} <span> @lang('labels.frontend.contact.title')</span></h2>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of contact section
        ============================================= -->
<section id="contact" class="contact-page-section">
    <div class="container">
	    <div class="row">
	            <div class="col-md-6">
	               <h3 class="heading"> Contact Details </h3> 
	               <h3 class="sub_heading"> Welcome to our Website. We are glad to have you around </h3>
	               <div class="hr"></div> 

	               <div class="col-md-12 details_wrapper">
		               <div class="details col-md-6">
		                   <div class="icon col-md-2">
		                        <i class="fas fa-phone-alt"></i>
		                    </div>
		                    <div class="content col-md-9">
		                        <h3 class="content-heading">Phone</h3>
		                        <p class="desc">+91 9148 672 911<br>+91 733 8089 114 </p>
		                    </div>
		               </div>
		               <div class="details col-md-6">
		                   <div class="icon col-md-3">
		                        <!-- <i class="fa fa-phone"></i> -->
		                        <img src="https://www.samitya.com/wp-content/uploads/2019/07/telephone-icon-1.png" alt="telephone-icon (1)" title="telephone-icon (1)" >
		                    </div>
		                    <div class="content col-md-9">
		                        <h3 class="content-heading">Landline</h3>
		                        <p class="desc"> 080-2955 2911 </p>
		                    </div>
		               </div>
	               </div>

	               <div class="col-md-12 details_wrapper">
		               <div class="details col-md-12">
		                   <div class="icon col-md-1">
		                        <i class="fas fa-map-marker-alt"></i>
		                    </div>
		                    <div class="content col-md-9">
		                        <h3 class="content-heading">Address</h3>
		                        <p class="desc">171, 2nd Main Road, 1st Block, 2nd Stage, Naagarabhaavi, Bengaluru, Karnataka 560072 </p>
		                    </div>
		               </div>
		           </div>

		           <div class="col-md-12 social">
			           <ul class="social_link">
			           	<li>
			           		<a class="facebook hasTooltip" href="https://www.facebook.com/samityaonline/" target="_self">
			           			<i class="fab fa-facebook-f"></i>
			           		</a>
			           	</li>
			           	<li>
			           		<a class="twitter hasTooltip" href="https://twitter.com/samityalearning" target="_self">
			           			<i class="fab fa-twitter"></i>
			           		</a>
			           	</li>
			           	<li>
			           		<a class="google-plus hasTooltip" href="https://www.samitya.com/contact/#" target="_self">
			           			<i class="fab fa-google-plus-g"></i>
			           		</a>
			           	</li>
			           	<li>
			           		<a class="instagram hasTooltip" href="https://www.instagram.com/samityaonline" target="_self">
			           			<i class="fab fa-instagram"></i>
			           		</a>
			           	</li>
			           	<li>
			           		<a class="youtube hasTooltip" href="https://www.youtube.com/channel/UCdiZEEv63UNyURVnkCaj9pQ" target="_self">
			           			<i class="fab fa-youtube"></i>
			           		</a>
			           	</li>	
			           </ul>
		           </div>
	            </div>

	            <div class="col-md-6 ">
		        	<h3 class="heading">Get In Touch</h3> 
		           	<h3 class="sub_heading">Your email address will not be published. Required fields are marked.</h3>
		           	<div class="hr"></div>

		            <div class="contact_third_form">
		                <form class="contact_form" action="{{route('contact.send')}}" method="POST" >
		                    @csrf
		                    <div class="row">
		                        <div class="col-md-6">
		                            <div class="contact-info">
		                                <input class="name" name="name" type="text" placeholder="Name *">
		                                @if($errors->has('name'))
		                                    <span class="help-block text-danger">{{$errors->first('name')}}</span>
		                                @endif
		                            </div>

		                        </div>
		                        <div class="col-md-6">
		                            <div class="contact-info">
		                                <input class="email" name="email" type="email" placeholder="Email *">
		                                @if($errors->has('email'))
		                                    <span class="help-block text-danger">{{$errors->first('email')}}</span>
		                                @endif
		                            </div>
		                        </div>
		                        <div class="col-md-12">
		                            <div class="contact-info">
		                                <input class="text" name="subject" type="text" placeholder="Subject *">
		                                @if($errors->has('subject'))
		                                    <span class="help-block text-danger">{{$errors->first('subject')}}</span>
		                                @endif
		                            </div>
		                        </div>
		                    </div>
		                    <textarea name="message" rows="7" placeholder="Message *"></textarea>
		                    @if($errors->has('message'))
		                        <span class="help-block text-danger">{{$errors->first('message')}}</span>
		                    @endif
<!-- *****************************google captcha******************************* -->
		            @if(config('access.captcha.contact') == 1)
		            <div class="row">
			        <div class="form-group">
			          <div class="g-recaptcha vvcapcha" data-callback="recaptchaCallback" data-sitekey="{{ config('no-captcha_newsitekey') }}"></div>
			          <span id="error-captcha"></span>
			          		@if($errors->has('g-recaptcha-response'))
		                        <span class="help-block text-danger">{{$errors->first('g-recaptcha-response')}}</span>
		                    @endif
			        </div>

						</div>
				    @endif                       
<!-- *****************************google captcha******************************* -->
		                    <div class="nws-button text-center  gradient-bg text-uppercase">
		                        <button class="text-uppercase" onclick="login_captcha()" type="submit" value="Submit">Submit </button>
		                    </div>
		                </form>
		            </div>
	        	</div>
	        	
	    </div>
    </div>

</section>

<section class="map">
	@if($contact_data["location_on_map"]["status"] == 1)
	<div class="container">
		<!-- <div class="col-md-12"> -->
            <div id="contact-map" class="contact-map-section">
                {!! $contact_data["location_on_map"]["value"] !!}
            </div>
        <!-- </div> -->
	</div>
    @endif
</section>






    <!-- End of contact section
        ============================================= -->

    <!-- Start of contact area form
        ============================================= -->

    <!-- End of contact area form
        ============================================= -->


    <!-- Start of contact area
        ============================================= -->
    @include('frontend.layouts.partials.contact_area')
    <!-- End of contact area
        ============================================= -->


@endsection    


@push('after-scripts')
    <script src='https://www.google.com/recaptcha/api.js'></script>           

<script type="text/javascript">
	// Validate google reCaptcha
var checkCaptcha;
function login_captcha()
  {
    console.log("captcha");
    $("#error-captcha").empty();
    //checkCaptcha = true;
    if(checkCaptcha) {
      $("#submitBtn").click();
      }
      else {

      console.log("insert captcha");
      // $("#error-captcha").append('<div class="parsley-errors-list filled" id="parsley-id-5"><span class="parsley-type">Please Insert the Captcha.</span></div>');
      return false;
  // alert('Please Insert the Captcha');
    }
  }
function recaptchaCallback() {
//alert("callback working");
  checkCaptcha = true;
  $("#error-captcha").empty();
  };
  $(document).ready(function(){
  var checkCaptcha = false;
  $("#error-captcha").empty();
});
</script>
@endpush