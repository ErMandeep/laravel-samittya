/*Created By Nikolas Magno - https://github.com/nikolasmagno/jquery-weekdays*/
$(function($){
    $.fn.weekdays = function(options){
        options = consolideOptions(options);
// console.log("fitst");
        var $this =  $(this);
        var $html = $("<ul class="+options.listClass+">");

        $this.data({
            days: options.days,
            selectedIndexes: options.selectedIndexes
        });
        var g = new Array("mon", "tue", "wed" , "thur", "fri" , "sat", "sun");

        $($this.data().days).each(function(index, item){
            var selected = $this.data().selectedIndexes.includes(index);
            var $liElement = $("<li data-day=" + g[index] + " class=" + options.itemClass + " selected=" + selected + ">" + item + "</li>");


            $liElement.on('click',function(item){
                if(options.singleSelect)
                   singleSelectMode(options, $html);

                var $li = $(item.target); 
                toggleSelection($li, options);
            });

            if(selected)
                $liElement.toggleClass(options.itemSelectedClass);

            $liElement.prop('selected', selected);

            $html.append($liElement);
        });

        $this.append($html); 	
    };
    // ************************

    $.fn.weekdays.days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]; 

    $.fn.selectedIndexes = function(){
// console.log("selectedIndexes");

        return $(this).find('li')
            .filter(function(index,a){
                return a.selected; 
            })
            .map(function(index,item){
                return $(item).attr("data-day"); 
            });
    };

    $.fn.selectedDays = function(){
// console.log("selectedDays");

        var $this = $(this);

        return $(this).find('li')
            .filter(function(index,a){ 
               
                return a.selected; 
            })
            .map(function(index,item){ 
                return $this.data().days[$(item).attr("data-day")]; 
            });
    }; 

    function consolideOptions(options){

        options = options ? options : {};
        options.days = options.days ? options.days : $.fn.weekdays.days;
        options.selectedIndexes = options.selectedIndexes ? options.selectedIndexes : [];
        options.listClass = options.listClass ? options.listClass : 'weekdays-list';
        options.itemClass = options.itemClass ? options.itemClass : 'weekdays-day';
        options.itemSelectedClass = options.itemSelectedClass ? options.itemSelectedClass : 'weekday-selected';
        options.singleSelect = options.singleSelect ? options.singleSelect : false;
        // options.time = $.fn.weekdays.days;

// console.log(options);
        return options;
    }

    function singleSelectMode(options, $html){
// console.log("singleSelectMode");

         $html.find('li')
              .each(function(index,item){ 
                       var $li = $(item);

                       $li.prop('selected', false);
                       $li.removeClass(options.itemSelectedClass);
                   });
    }

    function toggleSelection($li, options){
         var selected = !$li.prop('selected')
         $li.prop('selected', selected);
         var select = $li.prop('selected', selected);
         // console.log(select[0]['dataset']['time']);
         // console.log(select[0]['dataset']['day']);
         // console.log($li.prop('selected', selected));
         // console.log(options.itemSelectedClass);
        
         $li.toggleClass(options.itemSelectedClass);

         // *******************************









if(selected){
    // alert("cejh");


    console.log('click');
    alert('click');

// *****************************************************************
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




 // var url = "www.foo.com/test?name=kevin&gender=Male&id=1234";
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


// *************************************************************
if(select[0]['dataset']['day']){
var day = getUrlParameter('day');

var str2 = select[0]['dataset']['day'];
 // add or remove days in url
if(day != null ){
    if(day.indexOf(str2) != -1){

    var y = day.split(",");
     if(y.length == 1){
      var url = location.href;
location.href = removeURLParameter(url, 'day');
       var day = 'all';
     }else{
    y = $.grep(y, function(value) {
      return value != str2;
    });

    var day = y.toString();      

     }



    }else{
        var n = day.split(",");
        n.push(str2);
        var day = n.toString();
        // var day  = day+','+ str2;

    }
}




// *************************************************************
// Add or remove days in url

           if (window.location.href.indexOf("?sort=no") > -1) {
                    if (window.location.href.indexOf("day") > -1) {

                      if(day == 'all'){
                location.href = removeURLParameter(url, 'day');
                      }else{
                      var text = location.href;
                       var href = new URL(text);
                       href.searchParams.set('day', day);
                         var url = href.toString();
                         location.href = href.toString();
                         console.log(url);

                      }


                         // alert("hhhhhhhhhh");
                    }else{
                             location.href = location.href+'&day=' + select[0]['dataset']['day'];

                    }
            
                }else{
location.href = location.href+'?sort=no&day=' + select[0]['dataset']['day'];
                    }

}
// *************************************************************
// Add or remove time in url


if(select[0]['dataset']['time']){

var time = getUrlParameter('time');
console.log(time+ "check time");

var checktime = select[0]['dataset']['time'];
console.log(checktime);
 // add or remove days in url
if(time != null ){
    if(time.indexOf(checktime) != -1){

    var x = time.split(",");
     if(x.length == 1){
      var url = location.href;
location.href = removeURLParameter(url, 'time');
       var time = 'all';
     }else{
          x = $.grep(x, function(value) {
      return value != checktime;
    });

    var time = x.toString();
     }



    }else{
        var n = time.split(",");
        n.push(checktime);
        var time = n.toString();

    }
}


           if (window.location.href.indexOf("?sort=no") > -1) {
                    if (window.location.href.indexOf("time") > -1) {

                      if(time == 'all'){
                location.href = removeURLParameter(url, 'time');
                      }else{

                      var text = location.href;
                       var href = new URL(text);
                       href.searchParams.set('time', time);
                         var url = href.toString();
                         location.href = href.toString();
                         console.log(url);
                      }


                         // alert("hhhhhhhhhh");
                    }else{
                  location.href = location.href+'&time=' + select[0]['dataset']['time'];

                    }
            
                }else{
location.href = location.href+'?sort=no&time=' + select[0]['dataset']['time'];
                    }



}






// *************************************************************




}
// *************************************************************






    }


// time**************************************************************************************************************

    $.fn.weektime = function(options){
        options = consolideOptions(options);
console.log(options);
        var $this =  $(this);
        var $html = $("<ul class="+options.listClass+">");

        $this.data({
            time: options.time,
            selectedIndexes: options.selectedIndexes
        });



        var g = new Array("morning", "late-morning", "afternoon" , "late-afternoon", "evening" , "late-evening", "night" , "late-night");
        $($this.data().time).each(function(index, item){

            var selected = $this.data().selectedIndexes.includes(index);
                  // alert(value);
            var $liElement = $("<li data-time=" + g[index] + " class=" + options.itemClass + " selected=" + selected + ">" + item + "</li>");


            // console.log(item+"indeeee");

            $liElement.on('click',function(item){

                if(options.singleSelect)
                   singleSelectMode(options, $html);

                var $li = $(item.target); 
                toggleSelection($li, options);
                // console.log($li);
            });

            if(selected)
                $liElement.toggleClass(options.itemSelectedClass);

            $liElement.prop('selected', selected);

            $html.append($liElement);
        });

        $this.append($html);    
    };


    $.fn.weekdays.days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]; 

    $.fn.selectedIndexes = function(){
// console.log("selectedIndexes");

        return $(this).find('li')
            .filter(function(index,a){
                return a.selected; 
            })
            .map(function(index,item){
                return $(item).attr("data-day"); 
            });
    };

    $.fn.selectedDays = function(){
// console.log("selectedDays");

        var $this = $(this);

        return $(this).find('li')
            .filter(function(index,a){ 
               
                return a.selected; 
            })
            .map(function(index,item){ 
                return $this.data().days[$(item).attr("data-day")]; 
            });
    }; 

    function consolideOptions(options){

        options = options ? options : {};
        options.days = options.days ? options.days : $.fn.weekdays.days;
        options.selectedIndexes = options.selectedIndexes ? options.selectedIndexes : [];
        options.listClass = options.listClass ? options.listClass : 'weekdays-list';
        options.itemClass = options.itemClass ? options.itemClass : 'weekdays-day';
        options.itemSelectedClass = options.itemSelectedClass ? options.itemSelectedClass : 'weekday-selected';
        options.singleSelect = options.singleSelect ? options.singleSelect : false;
        // options.time = $.fn.weekdays.days;

// console.log(options);
        return options;
    }

    function singleSelectMode(options, $html){
// console.log("singleSelectMode");

         $html.find('li')
              .each(function(index,item){ 
                       var $li = $(item);

                       $li.prop('selected', false);
                       $li.removeClass(options.itemSelectedClass);
                   });
    }

    function toggleSelection($li, options){
         var selected = !$li.prop('selected')
         $li.prop('selected', selected);
         var select = $li.prop('selected', selected);
         // console.log(select[0]['dataset']['time']);
         // console.log(select[0]['dataset']['day']);
         // console.log($li.prop('selected', selected));
         // console.log(options.itemSelectedClass);

         alert("jsdhfjshjfhjsdhjfhsjdhfjhsjd");
        
         $li.toggleClass(options.itemSelectedClass);
       }





}); 