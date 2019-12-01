@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', 'Live Classes | '. app_name() )

@push('after-styles')
<link href=" {{ asset('assets/css/jquery-weekdays.css') }}  " rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/icofont.min.css">
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



/*#target {
  background:#0099cc;
  width:584px;
  z-index: 999;
  padding:5px;
  display:none;
  position: absolute;
}
*/
#target {
    background: #fff;
    width: 584px;
    z-index: 999;
    padding: 5px;
    display: none;
    margin: 4px -175px;
    position: absolute;
    box-shadow: 0px 0px 10px #333;
}



#pricebar{
    z-index: 999;
  /*padding:5px;*/
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

    .filtr{
      border-radius: 15px;
      margin: -90px auto;
    position: relative;
    background: #fff;
    padding: 80px 0px;
    box-shadow: 0px 0px 10px 1px #333;
}
.padding-0{
    padding-right:0;
    padding-left:0;
 width: 70%;
}
.larg{
 height: calc(1.25rem + 28px) !important;
 border-radius: 0px;
}
.ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
    width: 100% !important;
    float: left;
}
#newslider .ui-widget-header {
    background-color: #333333;
}
.course-page-section {
    padding: 40px 0px 0px;
}
.left{
  float: left;
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
.new-price{
      display: block;
    background: rgb(255, 255, 255);
    padding: 20px;
    box-shadow: rgb(51, 51, 51) 0px 0px 10px;
    width: 100%;
    margin: 2px 0px;
} 
.main{
  width: 100%;
    background: #fff;
}
.price-box{
  padding: 20px;
}
.main-color{
  color: #3d2d48;
}
.search-bar-font{
  font-size: 13px;
}
.modal-header .close {
    padding: 1rem;
    margin: -14px -7px 0px 0px;
}
.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
}


.time-first{
      position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    cursor: pointer;
    height: 75px;
    width: 125px;
    text-align: center;
}
.time-second{
      position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    cursor: pointer;
    height: 75px;
    width: 125px;
    text-align: center;
}
.new-days{
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    cursor: pointer;
    width: 72px;
    text-align: center;
}

      .ico-title {
        font-size: 2em;
      }
      .iconlist {
        margin: 0;
        padding: 0;
        list-style: none;
        text-align: center;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
      }
      .iconlist li {
        position: relative;
        margin: 5px;
        width: 150px;
        cursor: pointer;
      }
      .iconlist li .icon-holder {
        position: relative;
        text-align: center;
        border-radius: 3px;
        overflow: hidden;
        padding-bottom: 5px;
        background: #ffffff;
        border: 1px solid #E4E5EA;
        transition: all 0.2s linear 0s;
      }
      .iconlist li .icon-holder:hover {
        background: #00C3DA;
        color: #ffffff;
      }
      .iconlist li .icon-holder:hover .icon i {
        color: #ffffff;
      }
      .iconlist li .icon-holder .icon {
        padding: 20px;
        text-align: center;
      }
      .iconlist li .icon-holder .icon i {
        font-size: 3em;
        color: #1F1142;
      }
      .iconlist li .icon-holder span {
        font-size: 14px;
        display: block;
        margin-top: 5px;
        border-radius: 3px;
      }


@media only screen and (max-width:991px){

#target {
    background: #fff;
    width: 584px;
    z-index: 999;
    padding: 5px;
    display: none;
    margin: 4px -390px;
    position: absolute;
    box-shadow: 0px 0px 10px #333;
}


}
@media (min-width: 992px) and (max-width: 1024px) {


#target {
    background: #fff;
    width: 584px;
    z-index: 999;
    padding: 5px;
    display: none;
    margin: 4px -248px;
    position: absolute;
    box-shadow: 0px 0px 10px #333;
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
                <span>@if(isset($category)) {{$category->name}} @else Live Courses @endif </span>
                    </h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->
<!-- ***************************************************************************************************************************** -->
          <div class="container">


              <div class="row new-filter" style="margin: 0 15px;">

    <div class="col-md-2 padding-0">

          <select id="Budgetlevel" class="form-control larg search-bar-font">
            <option> </option>     
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



    <div class="col-md-2 padding-0">

          <select id="sortBy" class="form-control larg search-bar-font">
              <option value=""  hidden="true"></option>
   @foreach($languages as $language)  
              <option value="{{$language}}">{{$language}}</option>
   @endforeach           
          </select>



  </div>
<div class="col-md-2 padding-0">
      <button class="pricebar form-control larg search-bar-font"> <span class="left"> Price </span> <input type="text" id="newamount" style="width: 100%;" readonly ></button>
      <div id="pricebar" class="new-price">
      <input type="hidden" id="newamount" readonly >
      <div id="newslider" class="form-control d-inline w-50"></div>
      </div>
</div>
<div class="col-md-2 padding-0">
      <button class="toggle form-control larg search-bar-font"> Time </button>
                        <div id="target">
                      
      <td class="container">
        <div class="content">

          <table class="main">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td class="wrapper">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="price-box">
                      <!-- <h1>Week Day Picker Examples</h1> -->
                      <p class="main-color">TIME OF THE DAY</p>
                   
                      <div id="time1"> </div>
                      <div id="time2"> </div>
                      <!-- <div id="weekdaysCustom"> </div> -->
                      <p class="main-color">DAYS OF THE WEEK</p>
                      <div id="weekdays"> </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- END MAIN CONTENT AREA -->
          </table>

          <!-- START FOOTER -->
          <div class="footer">
            <table border="0" cellpadding="0" cellspacing="0"></table>
          </div>
          <!-- END FOOTER -->

          <!-- END CENTERED WHITE CONTAINER -->
        </div>
      </td>

                    </div>
</div>
<div class="col-md-2 padding-0">


<select id="searchreview" class="form-control larg search-bar-font"> 
    <!-- <option value="0">  Select Rating</option> -->
    <option value="1">  </option> 
    <option value="2">   </option> 
    <option value="3" selected="">    </option> 
    <option value="4">     </option> 
    <option value="5" >      </option> 
</select>

</div>
    </div>


      </div>
<!-- ***************************************************************************************************************************** -->

    <!-- Start of course section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row">



            </div>
        </div>


@if($courses != 'empty')

        <div class="container">
          <div class="col-md-12">
            <div class="live_index row other_courses subscribe-block">
                @foreach($courses as $item)
                  <div class="card col-md-6 col-lg-4"> 
                      <a href="{{ route('liveclass.show', [$item->slug]) }}">
                          <div class="thumbnailBox">
                              <div class="heart"><img src="{{ asset('assets/images/heart.svg') }}"></div>
                              <div class="thumbPic-wrapper" >
                                  <div class="thumbPic" style="background-image: url({{ asset('storage/uploads/'.$item->course_image) }})">
                                      <div class="videobutton trailer">
                                        <img src="{{asset('assets/images/cam.svg')}}">
                                        <input type="hidden" name="videolink" value="{{$item->videolink}}">
                                      </div>
                                  </div>
                               </div>
                                   <div class="thumb-content">
                                        <div class="rev">
                                         <!--  Rating By theme -->
                                        
                                          <span class="rev-am">({{count(@$item->reviews) }} reviews)</span>
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
            </div>
             
              </div>    
          </div>
                                  <div class="couse-pagination text-center ul-li">
                            {{ $courses->links() }}

                        </div>


@elseif($courses == 'empty')

 <div class="jumbotron text-center" style="background-color: #fff;">
  <h3 >No Result Found</h3>

</div>

@endif      
    </section>
    <!-- End of course section
        ============================================= -->
 <section id="course-page1" class="course-page-section1">

       
</section>

    <!-- Start of best course
   =============================================  -->
    <!-- @include('frontend.layouts.partials.browse_courses') -->
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
  <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

 

    <script>
        $(document).ready(function () {


  $(document).on("click", function(event) {
    var trigger = $(".toggle")[0];
    var dropdown = $("#target");
    if (dropdown !== event.target && !dropdown.has(event.target).length && trigger !== event.target) {
      $("#target").hide();
    }
  });
    $(document).on("click", function(event) {
    var trigger = $(".pricebar")[0];
    var dropdown = $("#pricebar");
    if (dropdown !== event.target && !dropdown.has(event.target).length && trigger !== event.target) {
      $("#pricebar").hide();
    }
  });
 // $('#time1 ul li').addClass('current');


      $('.toggle').click(function() {
      $('#target').toggle();
      });

      $('.pricebar').click(function() {
      $('#pricebar').toggle();
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
                    location.href = '{{route('liveclass.alllive')}}';
                }
            })

// ***************************************************   price    *********************************************************************

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
                    location.href = '{{route('liveclass.alllive')}}';
                }
            })
// ***************************************************   time    *********************************************************************


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



                    // console.log($(this).val());
                    // location.href = location.href+'&time=' + $(this).val();
                } else {
                    location.href = '{{route('liveclass.alllive')}}';
                }
            })

// ***************************************************   review    *********************************************************************


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



                    // console.log($(this).val());
                    // location.href = location.href+'&time=' + $(this).val();
                } else {
                    location.href = '{{route('liveclass.alllive')}}';
                }
            })

// ***************************************************   budget level    *********************************************************************
            $(document).on('change', '#Budgetlevel', function () {
                if ($(this).val() != "") {
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

// *****************************************************************************************************************************************
     

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
            $('#searchtime').find('option[value="' + "{{request('rating')}}" + '"]').attr('selected', true);
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
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
       

// **********************************************************************************
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
// **********************************************************************************

// *********************



  } );



    </script>

     <!-- <script src='assets/js/jquery-weekdays.js'></script> -->
  <script type="text/javascript" src=" {{ asset('assets/js/jquery-new-weekdays.js') }}   "></script>
  <script type="text/javascript" src=" {{ asset('assets/js/jquery-time.js') }} "></script>
  <script type="text/javascript" src="{{ asset('assets/js/jquery-time2.js') }}  "></script>
  <script>

$("#Budgetlevel").select2({
          placeholder: "Select Budget",
          minimumResultsForSearch: Infinity,
          // allowClear: true,
        });

        $('#skill').select2({
          placeholder: "Skill Level",
          minimumResultsForSearch: Infinity,
          // allowClear: true,
      });
        $('#sortBy').select2({
          placeholder: "Select Language",
      });


    $(function () {
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

var newday = getUrlParameter('day');

// *********************
if(newday != null ){
  var y = newday.split(",");

    var d = [];
    var item_id = ['mon', 'tue', 'wed', 'thur', 'fri' , 'sat', 'sun'];
    $.each(item_id, function(idx, value) {
        if ($.inArray(value, y) !== -1) {
        if(value == 'mon'){d.push( 0 ) }
        if(value == 'tue'){d.push(1) }  
        if(value == 'wed'){d.push(2) }  
        if(value == 'thur'){d.push(3) }
        if(value == 'fri'){d.push(4) }
        if(value == 'sat'){d.push(5) } 
        if(value == 'sun'){d.push(6) }  
        } else {
      
           
        }
    });
  }
  //************************************************* for times *****************************************//

var newtime = getUrlParameter('time');

// if(newtime != null ){
//   var x = newtime.split(",");

//     var t = [];
//     var item_id = ['morning', 'late-morning', 'afternoon', 'late-afternoon', 'evening' , 'late-evening', 'night', 'late-night'];
//     $.each(item_id, function(idx, value) {
//         if ($.inArray(value, x) !== -1) {
//         if(value == 'morning'){t.push( 0 ) }
//         if(value == 'late-morning'){t.push(1) }  
//         if(value == 'afternoon'){t.push(2) }  
//         if(value == 'late-afternoon'){t.push(3) }
//         if(value == 'evening'){t.push(4) }
//         if(value == 'late-evening'){t.push(5) } 
//         if(value == 'night'){t.push(6) }  
//         if(value == 'late-night'){t.push(7) }  
//         } else {
      
           
//         }
//     });
//     console.log(t);

// }
  //************************************************* for times *****************************************//
    var icon1 = [
    " <img src=   {{ asset('assets/images/morning.png') }}  > <br> 6-9 <br> morning",
    "  <img src=  {{ asset('assets/images/late-morning.png') }} > <br> 9-12 <br> late morning",
    " <img src=  {{ asset('assets/images/afternoon.png') }} > <br>12-3 <br> afternoon", 
    " <img src=  {{ asset('assets/images/late-afternoon.png') }} > <br>3-6 <br> late afternoon"
    ];    
if(newtime != null ){
  var x = newtime.split(",");

    var t1 = [];
    var item_id = ['morning', 'thesaurus', 'afternoon', 'gloaming'];
    $.each(item_id, function(idx, value) {
        if ($.inArray(value, x) !== -1) {
        if(value == 'morning'){t1.push( 0 )
    icon1[0] = " <img src= {{ asset('assets/images/morning-white.png') }} > <br> 6-9 <br> morning";

         }
        if(value == 'thesaurus'){t1.push(1) 

    icon1[1] = " <img src= {{ asset('assets/images/late-morning-white.png') }} > <br> 9-12 <br> late Morning";

        }  
        if(value == 'afternoon'){t1.push(2)

    icon1[2] = " <img src= {{ asset('assets/images/afternoon-white.png') }} > <br> 12-3 <br> afternoon";
         }  
        if(value == 'gloaming'){t1.push(3) 

    icon1[3] = " <img src=  {{ asset('assets/images/late-afternoon-white.png') }} > <br> 3-6 <br> late afternoon";

        }
        // if(value == 'evening'){t.push(4) }
        // if(value == 'late-evening'){t.push(5) } 
        // if(value == 'night'){t.push(6) }  
        // if(value == 'late-night'){t.push(7) }  
        } else {
      
           
        }
    });
    // console.log(icon1);




}

    var icon2 = [
    " <img src={{ asset('assets/images/evening.png') }}> <br> 6-9 <br> evening",
    "  <img src=  {{ asset('assets/images/late-evening.png') }}  > <br> 9-12 <br> late evening",
    " <img src=    {{ asset('assets/images/night.png') }}> <br>12-3 <br> night", 
    " <img src=   {{ asset('assets/images/late-night.png') }}> <br>3-6 <br> late night"
    ];  
if(newtime != null ){
  var x = newtime.split(",");

    var t2 = [];

    var item_id = ['evening' , 'sunset', 'night', 'darkness'];
    $.each(item_id, function(idx, value) {
        if ($.inArray(value, x) !== -1) {
        // if(value == 'morning'){t.push( 0 ) }
        // if(value == 'late-morning'){t.push(1) }  
        // if(value == 'afternoon'){t.push(2) }  
        // if(value == 'late-afternoon'){t.push(3) }
        if(value == 'evening'){t2.push(0) 
    icon2[0] = " <img src= {{ asset('assets/images/evening-white.png') }} > <br> 6-9 <br> evening";

        }
        if(value == 'sunset'){t2.push(1)
    icon2[1] = " <img src= {{ asset('assets/images/late-evening-white.png') }} > <br> 9-12 <br> late evening";

         } 
        if(value == 'night'){t2.push(2) 

    icon2[2] = " <img src= {{ asset('assets/images/night-white.png') }} > <br> 12-3 <br> night";
        }  
        if(value == 'darkness'){t2.push(3) 

    icon2[3] = " <img src= {{ asset('assets/images/late-night-white.png') }} > <br> 3-6 <br> late night";
        }  
        } else {
      
           
        }
    });
    // console.log(t2);

}



// *******************************new code*****************************************
      $('#weekdays').weekdays({
        selectedIndexes: d,
      });

      //       $('#weekdaysCustom').daytime({
      //   days: ["6am-9am", "9am-12pm", "12pm-3pm", "3pm-6pm", "6pm-9pm", "9pm-12am", "12am-3am" , "3am-6am"],
      //   selectedIndexes: t,
      // });
// *******************************new code*****************************************
            $('#time1').daytime({
        days: icon1,
        // days: ["6-9 Morning", "9-12 Late morning", "12-3 Afternoon", "3-6 Late afternoon", "6-9 Evening", "9-12 Late evening", "12-3 Night" , "3-6 Late night"],
        selectedIndexes: t1,
        // singleSelect: true,
      });

            $('#time2').daytime2({
        days: icon2,
        // days: ["6-9 Morning", "9-12 Late morning", "12-3 Afternoon", "3-6 Late afternoon", "6-9 Evening", "9-12 Late evening", "12-3 Night" , "3-6 Late night"],
        selectedIndexes: t2,
        // singleSelect: true,
      });


    });
  </script>
  <script type="text/javascript">
            $(document).ready(function () {

 $('#time1 ul li').addClass('time-first');
 $('#time2 ul li').addClass('time-second');
 $('#weekdays ul li').addClass('new-days');

        


});
  </script>
@endpush





   