/*Created By Nikolas Magno - https://github.com/nikolasmagno/jquery-weekdays*/
$(function($){
    $.fn.weekdays = function(options){
        options = consolideOptions(options);

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
        console.log($html);

        $this.append($html);    
    };

    $.fn.weekdays.days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]; 

    $.fn.selectedIndexes = function(){
        return $(this).find('li')
            .filter(function(index,a){
                return a.selected; 
            })
            .map(function(index,item){
                return $(item).attr("data-day"); 
            });
    };

    $.fn.selectedDays = function(){
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

        return options;
    }

    function singleSelectMode(options, $html){
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
         $li.toggleClass(options.itemSelectedClass);
         var select = $li.prop('selected', selected);
         console.log("weekkk");
         console.log(select[0]['dataset']['day']);
         console.log($li.prop('selected', selected));
         // console.log(options.itemSelectedClass);
// **********************************************************************************************************************
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
// if(select[0]['dataset']['day']){
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
 // alert("test");
 // alert(str2);
 
                             location.href = location.href+'&day=' + select[0]['dataset']['day'];

                    }
            
                }else{

location.href = location.href+'?sort=no&day=' + select[0]['dataset']['day'];
                    }

}





// **********************************************************************************************************************






    // }
}); 