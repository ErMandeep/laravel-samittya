@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <style>
      
        .couse-pagination li.active {
            color: #333333 !important;
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
            background-color: white;
            border: none;

        }

        ul.pagination {
            display: inline;
            text-align: center;
        }

       .wd-33{
            width: 25% !important;
        }
        .w-50{
             width: 90%!important;
        }
        #slider-range {
    width: 100% !important;
    float: right;
    /* margin-top: 6px; */
}
#newslider .ui-widget-header {
    background-color: #333333;
}


#target {
  /*background:#0099cc;*/
  /*width:584px;*/
  z-index: 999;
  padding:5px;
  display:none;
  position: absolute;
}

.Hide
{
  display:none;
}


/**********toggle div css********/
    table {
      border-collapse: separate;
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      width: 100%;
    }

    table td {
      font-size: 14px;
      vertical-align: top;
    }

    @media all {
      .ExternalClass {
        width: 100%;
      }

      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass font,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }

      .apple-link a {
        color: inherit !important;
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
        text-decoration: none !important;
      }

      .btn-primary table td:hover {
        background-color: #34495e !important;
      }

      .btn-primary a:hover {
        background-color: #34495e !important;
        border-color: #34495e !important;
      }
    } 
    .red
{
    background-color:#3d2d48;
    color: white;
}       
.red:hover {
    background-color: #3d2d48;
    color: white;
}
@import url(http://weloveiconfonts.com/api/?family=entypo);

[data-icon]:after {
  font-family: "entypo";
  content: attr(data-icon);
  speak: none;
}
input[type="search"] {
  width: 196px;
  height: 36px;
  padding: 0;
  line-height: 32px;
  background: #56e2c9;
  color: #fff;
  border: 0;
  font: bold 14px/20px "Arial", sans-serif;
  text-indent: 12px;
  outline: none;
  -webkit-appearance: textfield;
}
input[type="search"]::-webkit-input-placeholder {
  color: transparent;
}
input[type="search"]::-moz-input-placeholder {
  color: transparent;
}
input[type="search"]:-ms-input-placeholder {
  color: transparent;
}
input[type="search"]::-webkit-search-cancel-button, input[type="search"]::-webkit-search-results-button, input[type="search"]::-webkit-search-results-decoration {
  -webkit-appearance: none;
  width: 32px;
  height: 32px;
  line-height: 30px;
  color: white;
  text-align: left;
  cursor: pointer;
}
input[type="search"]::-webkit-search-cancel-button:before, input[type="search"]::-webkit-search-results-button:before, input[type="search"]::-webkit-search-results-decoration:before {
  position: absolute;
  content: 'x';
  font-style: normal;
}
input[type="search"]:invalid {
  border: 0 !important;
}
input[type="search"]:invalid::-webkit-search-cancel-button:before, input[type="search"]:invalid::-webkit-search-results-button:before, input[type="search"]:invalid::-webkit-search-results-decoration:before {
  font-family: "entypo";
  content: attr(data-icon);
}
input[type="search"] + label {
  position: absolute;
  margin: 8px 0 0 -30px;
  cursor: pointer;
}
input[type="search"] + label:before {
  position: absolute;
  content: 'Choisir';
  left: -152px;
  width: 180px;
  color: #fff;
  font: bold 14px/20px "Arial", sans-serif;
  text-align: left;
}
input[type="search"] + label:after {
  margin-left: 7px;
  color: #fff;
  content: '\25BE';
}

input[type="search"]:focus::-webkit-input-placeholder {
  font-weight: normal;
  color: #fff;
}
input[type="search"]:focus::-moz-input-placeholder {
  font-weight: normal;
  color: #fff;
}
input[type="search"]:focus:-ms-input-placeholder {
  font-weight: normal;
  color: #fff;
}
input[type="search"]:focus:invalid + label:before {
  display: none;
}
input[type="search"]:focus:invalid + label:after {
  margin-left: 4px;
  content: attr(data-icon);
}
input[type="search"]:valid + label {
  display: none;
}

.ui-autocomplete {
  width: 190px;
  max-height: 180px;
  padding: 0;
  margin: 0;
  overflow-y: auto;
  overflow-x: hidden;
  background: #2ec5aa;
  color: #fff;
  border-width: 4px 4px 4px 0;
  border-style: solid;
  border-color: #2ec5aa;
}
.ui-autocomplete ul {
  list-style: none;
}
.ui-autocomplete li {
  height: 36px;
  margin: 0;
  font: bold 14px/36px Arial, Helvetica, sans-serif;
  white-space: nowrap;
}
.ui-autocomplete li:hover {
  -moz-transition: background 0.3s ease-in;
  -o-transition: background 0.3s ease-in;
  -webkit-transition: background 0.3s ease-in;
  transition: background 0.3s ease-in;
  background: #3ed1b7;
}

.ui-autocomplete li a {
  display: block;
  padding: 0 12px;
  cursor: pointer;
}
.ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
    width: 103% !important;
    float: left;
}

.padding-0{
    padding-right:0;
    padding-left:0;
 width: 70% ;
}

.larg{
 height: calc(1.25rem + 28px) !important;
 border-radius: 0px;
 text-align: left;
  border: 1px solid #ced4da;
}

/* Base for label styling */
[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute;
  left: -9999px;
}
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative;
  padding-left: 1.95em;
  cursor: pointer;
}

/* checkbox aspect */
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  content: '';
  position: absolute;
  left: 0; top: 0;
  width: 1.25em; height: 1.25em;
  border: 2px solid #ccc;
  background: #fff;
  border-radius: 4px;
  box-shadow: inset 0 1px 3px rgba(0,0,0,.1);
}
/* checked mark aspect */
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '\2713\0020';
  position: absolute;
  top: .15em; left: .22em;
  font-size: 1.3em;
  line-height: 0.8;
  color: #09ad7e;
  transition: all .2s;
  font-family: 'Lucida Sans Unicode', 'Arial Unicode MS', Arial;
}
/* checked mark aspect changes */
[type="checkbox"]:not(:checked) + label:after {
  opacity: 0;
  transform: scale(0);
}
[type="checkbox"]:checked + label:after {
  opacity: 1;
  transform: scale(1);
}

.course-page-section {
    padding: 30px 0px 0px;
    /*padding: 0px 0px 0px;*/
}

.filtr{
      margin: -90px auto;
    position: relative;
    background: #fff;
    padding: 80px 0px;
    box-shadow: 0px 0px 10px 1px #333;
    border-radius: 15px;
}
.breadcrumb-section {
    /* background-image: url(/images/brt-1.jpg?8fa08d9…); */
    padding-bottom: 90px;
}
.new-filter{
  margin: 0px auto;
    position: relative;
    background: #fff;
    padding: 35px 20px;
    box-shadow: 0px 0px 10px -1px #333;
    border-radius: 10px;
}
.new-price {
    display: block;
    background: rgb(255, 255, 255);
    padding: 20px !important;
    box-shadow: rgb(51, 51, 51) 0px 0px 10px;
    width: 100%;
    margin: 2px 0px;
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
        <div class="container">


            <div class="row new-filter" style="margin: 0 20px;">
  <div class="col-md-2 padding-0">

        <select id="Budgetlevel" name="budget" class="form-control larg search-bar-font"> 
        <option></option>   
          <option value="budget">Budget</option>     
          <option value="premium">Premium</option>     
        </select>



</div>

  <div class="col-md-2 padding-0">

        <select id="skill" class="form-control larg search-bar-font">
            <option value="" hidden="true"></option>  
          <option value="Beginner">Beginner</option>     
          <option value="Intermediate">Intermediate</option>     
          <option value="Advance">Advance</option>     
        </select>



</div>



  <div class="col-md-3 padding-0">

        <select id="sortBy" class="form-control larg search-bar-font">
            <option value="" hidden="true">Select Language</option>
 @foreach($languages as $language)  
            <option value="{{$language}}">{{$language}}</option>
 @endforeach      
          <!-- <option value="Hindi">Hindi</option>     
          <option value="English">English</option>     
          <option value="Punjabi">Punjabi</option>   -->   
        </select>



</div>
<div class="col-md-2 padding-0">
      <button id="newprice" class="toggle form-control larg newamount_btn search-bar-font"> Price <input type="text" id="newamount" readonly ></button>
      <div id="target" class="new-price">
      <input type="hidden" id="newamount" readonly >
      <div id="newslider" class="form-control d-inline w-50"></div>
      </div>
</div>
<div class="col-md-3 padding-0">
        <select id="searchreview" class="form-control  larg search-bar-font"> 
                   <!-- <option value="0" >Select Rating</option> -->
                    <option value="1">  </option>                                 
                    <option value="2">   </option>                                 
                    <option value="3" selected>    </option>                                 
                    <option value="4">     </option>                                 
                    <option value="5" >      </option>                                 
        </select>

  <p id="freecourse_wrapper">
    @php
if(request('free') == 1){
$free = 'checked';
}

@endphp
    <input type="checkbox" style="float: right;" id="freecourse" {{@$free}} />
    <label for="freecourse" style="float: right;">Only Free Courses</label>
  </p>


</div>
    </div>


      </div>

<!-- *********************************************************************************************************************** -->

    <!-- Start of course section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
 

                    <div class="genius-post-item">
                        <div class="tab-container">


 
<!-- Start popular course
       ============================================= -->
       @if($courses != 'empty')

 <div class="container">

    <section id="popular-course" class=" teacher-thumbnail-section popular-course-section {{isset($class) ? $class : ''}}">
                   <div  class="card-columns row">
                @foreach($courses as $item)
                    <div class="card col-md-6 col-lg-4"> 
                        <a href="{{ route('courses.show', [$item->slug]) }}">
                        <div class="thumbnailBox">
                            <div class="heart"><img src="{{asset('assets/images/heart.svg')}}"></div>
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
                                    @if(@$item->display_duration)
                                    <div class="hrs">{{ $item->display_duration }}</div>
                                    @endif
                                    <div class="price">
                                      @if(@$item->free)
                                      <div class="Currency" >Free</div>
                                      @else
                                      <div class="Currency" >{{$appCurrency['symbol'].$item->sub_price_1 }}</div>
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
            <!-- <div class="samitya-button mt25">
              <a href="/regular-courses">View All</a>
          </div> -->
                </section>
        </div>





    <!-- End popular course
        ============================================= -->




                        </div>
                        <div class="couse-pagination text-center ul-li">
                            {{ $courses->links() }}
                        </div>
                    </div>


                </div>

 
            </div>
        </div>

        @elseif($courses == 'empty')

 <div class="jumbotron text-center" style="background-color: #fff;">
  <h3 >No Result Found</h3>

</div>

@endif  
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
     <script>
        $(document).ready(function () {

if (window.location.href.indexOf("free") > -1) {
$('select[name="budget"]').attr('disabled', 'disabled');
$("#newprice").attr("disabled", true);
$("#newamount").css("background-color", "transparent");
}


            $(document).on("click", function(event) {
    var trigger = $(".toggle")[0];
    var dropdown = $("#target");
    if (dropdown !== event.target && !dropdown.has(event.target).length && trigger !== event.target) {
      $("#target").hide();
    }
  });

          $('.toggle').click(function() {
          $('#target').toggle();
          });

});
</script>



    <script>
        $(document).ready(function () {


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

// var tech = getUrlParameter('language');
// ***************************************************   Skill    *********************************************************************


            $(document).on('change', '#skill', function () {
                if ($(this).val() != "") {
           if (window.location.href.indexOf("?sort=no") > -1) {
            if (window.location.href.indexOf("skill") > -1) {
              var text = location.href;
               var href = new URL(text);
               href.searchParams.set('skill', $(this).val());
                 var url = href.toString();
                 location.href = href.toString();
                 console.log(url);
                 // alert("hhhhhhhhhh");
            }else{
                     location.href = location.href+'&skill=' + $(this).val();

            }
            
                }else{
location.href = location.href+'?sort=no&skill=' + $(this).val();
                  }
                 
                } else {
                    location.href = '{{route('courses.allshow')}}';
                }
            })

// ***************************************************   Language    *********************************************************************


            $(document).on('change', '#sortBy', function () {
                if ($(this).val() != "") {
           if (window.location.href.indexOf("?sort=no") > -1) {
            if (window.location.href.indexOf("language") > -1) {
              var text = location.href;
               var href = new URL(text);
               href.searchParams.set('language', $(this).val());
                 var url = href.toString();
                 location.href = href.toString();
                 console.log(url);
                 // alert("hhhhhhhhhh");
            }else{
                     location.href = location.href+'&language=' + $(this).val();

            }
            
                }else{
location.href = location.href+'?sort=no&language=' + $(this).val();
                  }
                 
                } else {
                    location.href = '{{route('courses.allshow')}}';
                }
            })

// ***************************************************   Price    *********************************************************************

            $(document).on('change', '#searchprice', function () {
                if ($(this).val() != "") {

                  if (window.location.href.indexOf("?sort=no") > -1) {
                    
            if (window.location.href.indexOf("price") > -1) {
              var text = location.href;
               var href = new URL(text);
               href.searchParams.set('price', $(this).val());
                 var url = href.toString();
                 location.href = href.toString();
                 console.log(url);
                 // alert("hhhhhhhhhh");
            }else{
                     location.href = location.href+'&price=' + $(this).val();

            }


                  }else{
                    location.href = location.href+'?sort=no&price=' + $(this).val();
                  }



                } else {
                    location.href = '{{route('courses.allshow')}}';
                }
            })

// ***************************************************   Time    *********************************************************************


            $(document).on('change', '#searchtime', function () {
                if ($(this).val() != "") {

                  if (window.location.href.indexOf("?sort=no") > -1) {
              if (window.location.href.indexOf("time") > -1) {
                // console.log("dsfsddsfsdfs");
              var newtext = location.href;
               var href = new URL(newtext);
               href.searchParams.set('time', $(this).val());
                 var newurl = href.toString();
                 location.href = href.toString();
                 console.log(newurl);

            }else{
                     location.href = location.href+'&time=' + $(this).val();

            }                   

                  }else{
                    location.href = location.href+'?sort=no&time=' + $(this).val();
                  }               

                } else {
                    location.href = '{{route('courses.allshow')}}';
                }
            })

// ***************************************************   reviews    *********************************************************************


            $(document).on('change', '#searchreview', function(){
              if ($(this).val() != "") {

                  if (window.location.href.indexOf("?sort=no") > -1) {
              if (window.location.href.indexOf("rating") > -1) {
                // console.log("dsfsddsfsdfs");
              var newtext = location.href;
               var href = new URL(newtext);
               href.searchParams.set('rating', $(this).val());
                 var newurl = href.toString();
                 location.href = href.toString();
                 console.log(newurl);

            }else{
                     location.href = location.href+'&rating=' + $(this).val();

            }                   

                  }else{
                    location.href = location.href+'?sort=no&rating=' + $(this).val();
                  }               


                } else {
                    location.href = '{{route('courses.allshow')}}';
                }
            })

// ***************************************************   budget level    *********************************************************************
            $(document).on('change', '#Budgetlevel', function () {
                if ($(this).val() != "") {

if (window.location.href.indexOf("free") > -1) {
	return false;
}

           if (window.location.href.indexOf("?sort=no") > -1) {
            if (window.location.href.indexOf("budgets") > -1) {
              var text = location.href;
               var href = new URL(text);
               href.searchParams.set('budgets', $(this).val());
                 var url = href.toString();
                 location.href = href.toString();
                 console.log(url);
            
            }else{
                     location.href = location.href+'&budgets=' + $(this).val();

            }
            
                }else{
location.href = location.href+'?sort=no&budgets=' + $(this).val();
                  }
                 
                } else {
                    location.href = '{{route('courses.allshow')}}';
                }
            })


// *******************************************************************************************************************************************

            
            @if(request('language') != "")
            $('#sortBy').find('option[value="' + "{{request('language')}}" + '"]').attr('selected', true);
            @endif
            @if(request('price') != "")
            $('#searchprice').find('option[value="' + "{{request('price')}}" + '"]').attr('selected', true);
            @endif
            @if(request('range') != "")
            $('#amount').find('option[value="' + "{{request('time')}}" + '"]').attr('selected', true);
            @endif                        
            @if(request('rating') != "")
            $('#searchreview').find('option[value="' + "{{request('rating')}}" + '"]').attr('selected', true);
            @endif  
            @if(request('budgets') != "")
            $('#Budgetlevel').find('option[value="' + "{{request('budgets')}}" + '"]').attr('selected', true);
            @endif  
            @if(request('skill') != "")
            $('#skill').find('option[value="' + "{{request('skill')}}" + '"]').attr('selected', true);
            @endif    
        });






  $( function() {
// console.log(range+"ranggggggggggggg");

function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        return url;
    } else {
        return url;
    }
}



var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


var last = getUrlParameter('last');
if(last == null){
var last = 1400;
}
var start = getUrlParameter('start');
if(start == null){
  var start = 150;
}
// alert(range);

    

    $( "#slider-range" ).slider({
      range: true,
      min: 100,
      max: 2000,
      values: [start , last ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );

        if (window.location.href.indexOf("?sort=no") > -1) {
              if (window.location.href.indexOf("last") > -1) {
               // console.log("dsfsddsfsdfs");
               var newtext = location.href;
               var href = new URL(newtext);
               href.searchParams.set('last', ui.values[ 1 ]);
               href.searchParams.set('start', ui.values[ 0 ]);
               var newurl = href.toString();
               location.href = href.toString();
               // console.log(newurl);


            }else{
              // console.log("hvvvbvvbvbvbv");
                     location.href = location.href+'&start=' + ui.values[ 0 ]+'&last=' + ui.values[ 1 ];

            }                   

                  }else{
                    location.href = location.href+'?sort=no&start=' + ui.values[ 0 ]+ '&last='+ ui.values[ 1 ] ;
                  }   


      }
    });
    $( "#amount" ).val( "Rs." + $( "#slider-range" ).slider( "values", 0 ) +
      " - Rs." + $( "#slider-range" ).slider( "values", 1 ) );
       

// **********************************************free********************************************************************
    $( "#newslider" ).slider({
      range: true,
      min: 100,
      max: 2000,
      values: [start , last ],
      slide: function( event, ui ) {
        $( "#newamount" ).val( "Rs." + ui.values[ 0 ] + " - Rs." + ui.values[ 1 ] );

        if (window.location.href.indexOf("?sort=no") > -1) {
              if (window.location.href.indexOf("last") > -1) {
                // console.log("dsfsddsfsdfs");
              var newtext = location.href;
               var href = new URL(newtext);
               href.searchParams.set('last', ui.values[ 1 ]);
               href.searchParams.set('start', ui.values[ 0 ]);
                 var newurl = href.toString();
                 location.href = href.toString();
                 // console.log(newurl);


            }else{
              // console.log("hvvvbvvbvbvbv");
                     location.href = location.href+'&start=' + ui.values[ 0 ]+'&last=' + ui.values[ 1 ];

            }                   

                  }else{
                    location.href = location.href+'?sort=no&start=' + ui.values[ 0 ]+ '&last='+ ui.values[ 1 ] ;
                  }   


      }
    });
    $( "#newamount" ).val( "Rs." + $( "#newslider" ).slider( "values", 0 ) +
      " - Rs." + $( "#newslider" ).slider( "values", 1 ) );
// **********************************************free********************************************************************
$('#timebtn').click(function(){

  var free = getUrlParameter('free');
  
  if(free == null){
    var free = 0;
   }
  var text = 'Free';

    // save $(this) so jQuery doesn't have to execute again
    var $this = $(this).find('span');
    if ($this.text() === text) {
        // $this.text('Free Courses');
  
    } else {
      var free = 0;
     
        $this.text(text);
    }

           if (window.location.href.indexOf("?sort=no") > -1) {
              if (window.location.href.indexOf("free") > -1) {
               if(free == 1){
                var free =0;
               } else {
                var free = 1;
               }
              var newtext = location.href;
               var href = new URL(newtext);
               href.searchParams.set('free', free);
                 var newurl = href.toString();
                 location.href = href.toString();
                 console.log(newurl);

            }else{
                     location.href = location.href+'&free=' + 1;

            }                   

                  }else{
                    location.href = location.href+'?sort=no&free=' + 1;
                  }    



  $(this).toggleClass('red');

});

// *******************************************
 var ckbox = $('#freecourse');
    $('#freecourse').on('click',function () {


    // var free = getUrlParameter('free');
        if (ckbox.is(':checked')) {
            // alert('You have Checked it');
       
            var free = 1;
        } else {

            var free = 0;
        }


           if (window.location.href.indexOf("?sort=no") > -1) {
              if (window.location.href.indexOf("free") > -1) {
            
              var newtext = location.href;
            // alert(newtext);
    var newtext = removeURLParameter(newtext, 'free');


               // var href = new URL(newtext);
               // href.searchParams.set('free', free);
                 // var newurl = href.toString();
                 // location.href = href.toString();
                 location.href = newtext;
                 // console.log(newurl);

            }else{

    var url = location.href;
    var url = removeURLParameter(url, 'start');
    var url = removeURLParameter(url, 'last');
    var url = removeURLParameter(url, 'budgets');
    // alert(url);  

                     // location.href = location.href+'&free=' + 1;
                     location.href = url+'&free=' + 1;

            }                   

                  }else{
                    location.href = location.href+'?sort=no&free=' + 1;
                  }    




    });       




// *******************************************




  } );



    </script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>  
    <script>
        $(document).ready(function () {

        // $( "#Budgetlevel" ).selectmenu();
        $("#Budgetlevel").select2({
          placeholder: "Select Budget",
          minimumResultsForSearch: Infinity,

        });

        $('#skill').select2({
          placeholder: "Skill Level",
          minimumResultsForSearch: Infinity,
      });
        $('#sortBy').select2({
          placeholder: "Select Language",
      });

});
</script>



@endpush


   