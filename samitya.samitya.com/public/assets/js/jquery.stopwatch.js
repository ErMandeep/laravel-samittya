(function($){$.extend({stopwatch:{formatTimer:function(a){if(a<10){a='0'+a;}
return a;},startTimer:function(dir){var a;$.stopwatch.dir=dir;$.stopwatch.d1=new Date();switch($.stopwatch.state){case 'pause':$.stopwatch.t1=$.stopwatch.d1.getTime()-$.stopwatch.td;break;default:$.stopwatch.t1=$.stopwatch.d1.getTime();if($.stopwatch.dir==='cd'){$.stopwatch.t1+=parseInt($('#cd_seconds').val())*1000;}
break;}
$.stopwatch.state='alive';$('#'+$.stopwatch.dir+'_status').html('Running');$.stopwatch.loopTimer();},pauseTimer:function(){$.stopwatch.dp=new Date();$.stopwatch.tp=$.stopwatch.dp.getTime();$.stopwatch.td=$.stopwatch.tp-$.stopwatch.t1;$('#'+$.stopwatch.dir+'_start').val('Resume');$.stopwatch.state='pause';$('#'+$.stopwatch.dir+'_status').html('Paused');},stopTimer:function(){$('#'+$.stopwatch.dir+'_start').val('Restart');$.stopwatch.state='stop';$('#'+$.stopwatch.dir+'_status').html('Stopped');},resetTimer:function(){$('#'+$.stopwatch.dir+'_ms,#'+$.stopwatch.dir+'_s,#'+$.stopwatch.dir+'_m,#'+$.stopwatch.dir+'_h').html('00');$('#'+$.stopwatch.dir+'_start').val('Start');$.stopwatch.state='reset';$('#'+$.stopwatch.dir+'_status').html('Reset & Idle again');},endTimer:function(callback){$('#'+$.stopwatch.dir+'_start').val('Restart');$.stopwatch.state='end';if(typeof callback==='function'){callback();}},loopTimer:function(){var td;var d2,t2;var ms=0;var s=0;var m=0;var h=0;if($.stopwatch.state==='alive'){d2=new Date();t2=d2.getTime();if($.stopwatch.dir==='sw'){td=t2-$.stopwatch.t1;}else{td=$.stopwatch.t1-t2;if(td<=0){$.stopwatch.endTimer(function(){$.stopwatch.resetTimer();$('#'+$.stopwatch.dir+'_status').html('Ended & Reset');});}}
ms=td%1000;if(ms<1){ms=0;}else{s=(td-ms)/1000;if(s<1){s=0;}else{var m=(s-(s%60))/60;if(m<1){m=0;}else{var h=(m-(m%60))/60;if(h<1){h=0;}}}}
ms=Math.round(ms/100);s=s-(m*60);m=m-(h*60);$('#'+$.stopwatch.dir+'_ms').html($.stopwatch.formatTimer(ms));$('#'+$.stopwatch.dir+'_s').html($.stopwatch.formatTimer(s));$('#'+$.stopwatch.dir+'_m').html($.stopwatch.formatTimer(m));$('#'+$.stopwatch.dir+'_h').html($.stopwatch.formatTimer(h));$.stopwatch.t=setTimeout($.stopwatch.loopTimer,1);}else{clearTimeout($.stopwatch.t);return true;}}}});})(jQuery);