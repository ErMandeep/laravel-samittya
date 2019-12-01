$.ajaxSetup({headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}});$.ajax({url:'/courses_all',type:'post',data:{ReferenceId:'1'},success:function(response){$data=JSON.parse(response);jQuery('input.search_input').typeahead({hint:true,highlight:true,minLength:1},{name:'courses',limit:5,source:substringMatcher($data)});}});var substringMatcher=function(strs){return function findMatches(q,cb){var matches,substringRegex;matches=[];substrRegex=new RegExp(q,'i');$.each(strs,function(i,str){if(substrRegex.test(str)){matches.push(str);}});cb(matches);};};jQuery('input.search_input').on('typeahead:select',function(){$('#search_form').submit();})
$('.trailer').click(function(event){event.preventDefault();var vid=$(this).find('input[name=videolink]').val();function getId(url){var regExp=/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;var match=url.match(regExp);if(match&&match[2].length==11){return match[2];}else{return'error';}}
var myId=getId(vid);$.showYtVideo({videoId:myId});});$(document).ready(function(){setTimeout(function(){if(!Cookies.get('modalShown')){$("#onetym").modal('show');Cookies.set('modalShown',true);}else{}},3000);});


function shiftLeft() {
    const boxes = document.querySelectorAll(".box");
    const tmpNode = boxes[0];
    boxes[0].className = "box move-out-from-left";
    setTimeout(function() {
        if (boxes.length > 5) {
            tmpNode.classList.add("box--hide");
            boxes[5].className = "box move-to-position5-from-left";
        }
        boxes[1].className = "box move-to-position1-from-left";
        boxes[2].className = "box move-to-position2-from-left";
        boxes[3].className = "box move-to-position3-from-left";
        boxes[4].className = "box move-to-position4-from-left";
        boxes[0].remove();
        document.querySelector(".cards__container").appendChild(tmpNode);
    }, 500);
}

function shiftRight() {
    const boxes = document.querySelectorAll(".box");
    boxes[4].className = "box move-out-from-right";
    setTimeout(function() {
        const noOfCards = boxes.length;
        if (noOfCards > 4) {
            boxes[4].className = "box box--hide";
        }
        const tmpNode = boxes[noOfCards - 1];
        tmpNode.classList.remove("box--hide");
        boxes[noOfCards - 1].remove();
        let parentObj = document.querySelector(".cards__container");
        parentObj.insertBefore(tmpNode, parentObj.firstChild);
        tmpNode.className = "box move-to-position1-from-right";
        boxes[0].className = "box move-to-position2-from-right";
        boxes[1].className = "box move-to-position3-from-right";
        boxes[2].className = "box move-to-position4-from-right";
        boxes[3].className = "box move-to-position5-from-right";
    }, 500);
    }