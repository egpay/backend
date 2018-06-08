var audio_context;
var recorder;

function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);
    recorder = new Recorder(input, {
        numChannels: 1
    });
}

function startRecording(button) {
    recorder && recorder.record();
    $(button).removeClass('ft-mic').addClass('Blink text-danger ft-mic');
    //$(button).text(' Recording');
    $(button).attr('onclick','stopRecording(this)');
}

function stopRecording(button) {
    recorder && recorder.stop();
    $(button).removeClass('Blink text-danger').attr('onclick','startRecording(this)');

    createDownloadLink();
    recorder.clear();
}


function createDownloadLink() {
    recorder && recorder.exportWAV(function(blob) {
    });
}

window.onload = function init() {
    try {
        // webkit shim
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = ( navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia ||
        navigator.msGetUserMedia);
        window.URL = window.URL || window.webkitURL;

        audio_context = new AudioContext;
    } catch (e) {
        alert('No web audio support in this browser!');
    }

    navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
        //__log('No live audio input: ' + e);
    });
};