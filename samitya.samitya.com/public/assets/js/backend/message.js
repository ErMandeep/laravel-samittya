  var hostname = window.location.hostname;

    //webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream;                      //stream from getUserMedia()
var rec;                            //Recorder.js object
var input;                          //MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);

function startRecording() {
    console.log("recordButton clicked");


    var constraints = { audio: true, video:false }

    /*
        Disable the record button until we get a success or fail from getUserMedia() 
    */
        $(recordButton).toggleClass( 'disable');
    $(stopButton).toggleClass( 'disable');
    $(pauseButton).toggleClass( 'disable');
    

    /*
        We're using the standard promise based getUserMedia() 
        https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	    */
try {
navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {

    

    /*
        create an audio context after getUserMedia is called
        sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
        the sampleRate defaults to the one set in your OS for your playback device

    */
    audioContext = new AudioContext();

   /*  assign to gumStream for later use  */
    gumStream = stream;
    
    /* use the stream */
    input = audioContext.createMediaStreamSource(stream);

    /* 
        Create the Recorder object and configure to record mono sound (1 channel)
        Recording 2 channels  will double the file size
    */
    rec = new Recorder(input,{numChannels:1})

    //start the recording process
    rec.record()

    console.log("Recording started");

}).catch(function(err) {
    //enable the record button if getUserMedia() fails
   alert('There is an error While Recording Audio, Make Sure you plugged in the recording device');
    $(recordButton).removeClass( 'disable');
    $(stopButton).addClass( 'disable');
    $(pauseButton).addClass( 'disable');
});
}

catch(err) {
   alert('There is an error While Recording Audio, Make Sure you plugged in the recording device');
  
  $(recordButton).removeClass( 'disable');
    $(stopButton).addClass( 'disable');
    $(pauseButton).addClass( 'disable');
}


}

function pauseRecording(){
    if (rec.recording){
        //pause
        rec.stop();
        $(pauseButton).removeClass ( 'fa-pause');
        $(pauseButton).addClass( 'fa-play');
    }else{
        //resume
        rec.record()
        $(pauseButton).removeClass ( 'fa-play');
        $(pauseButton).addClass( 'fa-pause');

    }
}

function stopRecording() {
    console.log("stopButton clicked");

    //disable the stop button, enable the record too allow for new recordings
    $(recordButton).toggleClass( 'disable');
    $(stopButton).toggleClass( 'disable');
    $(pauseButton).toggleClass( 'disable');

    //reset button just in case the recording is stopped while paused

    
    //tell the recorder to stop the recording
    rec.stop();

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {

  var fd = new FormData();
  fd.append("audio_data",blob);
  fd.append('thread_id',$('#thread_id').val());
  fd.append('_token',$('meta[name="csrf-token"]').attr('content'));


    $.ajax({
    url: config.routes.send_audio,
    data: fd,
    type: 'POST',
    contentType: false, 
    processData: false,

    beforeSend:function() {
        $('.input_msg_write').css('display','none');
        $('.msg_loader').css('display','block');
    },
 	success: function(redirect) {
        $('.msg_loader').css('display','none ');
        $('.input_msg_write').css('display','block');

        location.href = redirect;
    },

	});  
}

 $('#uploadefile').click(function(){
	$('#file').click();
	})

 $('input[type="file"]').change(function(e){
            var fileName = e.target.files[0].name;

            $('.write_msg').css('display','none');

            $('#input_file .filename').text(fileName);
            $('#input_file').css('display','block');

        });

$('#removefile').click(function(e){

	$('#file').val('');

	$('.write_msg').css('display','block');
    $('#input_file .filename').text('');
    $('#input_file').css('display','none');

})