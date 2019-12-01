function __log(e, data) {
    log.innerHTML += "\n" + e + " " + (data || '');
}
var audio_context;
var recorder;

function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);
    __log('Media stream created.');
    __log("input sample rate " + input.context.sampleRate);
    input.connect(audio_context.destination);
    __log('Input connected to audio context destination.');
    recorder = new Recorder(input);
    $("#recordButton").removeClass("recordOff").addClass("recordOn");
    $("#recordHelp").fadeOut("slow");
    __log('Recorder initialised.');
}

function startRecording(button) {
    recorder && recorder.record();
    button.disabled = true;
    button.nextElementSibling.disabled = false;
    __log('Recording...');
}

function stopRecording(button) {
    recorder && recorder.stop();
    button.disabled = true;
    __log('Stopped recording.');
    createDownloadLink();
    recorder.clear();
}

function createDownloadLink() {
    recorder && recorder.exportWAV(function(blob) {});
}
window.onload = function init() {
    try {
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
        window.URL = window.URL || window.webkitURL;
        audio_context = new AudioContext;
        __log('Audio context set up.');
        __log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
    } catch (e) {}
    navigator.getUserMedia({
        audio: true
    }, startUserMedia, function(e) {
        __log('No live audio input: ' + e);
    });
};