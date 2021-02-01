
  function __log(e, data) {
    log.innerHTML += "\n" + e + " " + (data || '');
  }

  var audio_context;
  var recorder;

  function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);
    __log('Media stream created.' );
	__log("input sample rate " +input.context.sampleRate);

    // Feedback!
    //input.connect(audio_context.destination);
    __log('Input connected to audio context destination.');

    recorder = new Recorder(input, {
                  numChannels: 1
                });
    __log('Recorder initialised.');
  }
if (navigator.getUserMedia) {
  navigator.getUserMedia({
      audio: true
    },
    function(stream) {
      audioContext = new AudioContext();
      analyser = audioContext.createAnalyser();
      microphone = audioContext.createMediaStreamSource(stream);
      javascriptNode = audioContext.createScriptProcessor(2048, 1, 1);

      analyser.smoothingTimeConstant = 0.8;
      analyser.fftSize = 1024;

      microphone.connect(analyser);
      analyser.connect(javascriptNode);
      javascriptNode.connect(audioContext.destination);

      canvasContext = $("#canvas")[0].getContext("2d");

      javascriptNode.onaudioprocess = function() {
          var array = new Uint8Array(analyser.frequencyBinCount);
          analyser.getByteFrequencyData(array);
          var values = 0;

          var length = array.length;
          for (var i = 0; i < length; i++) {
            values += (array[i]);
          }

          var average = values / length;

//          console.log(Math.round(average - 40));

          canvasContext.clearRect(0, 0, 150, 300);
          canvasContext.fillStyle = '#BadA55';
          canvasContext.fillRect(0, 300 - average, 150, 300);
          canvasContext.fillStyle = '#262626';
          canvasContext.font = "48px impact";
          canvasContext.fillText(Math.round(average - 40), -2, 300);

        } // end fn stream
    },
    function(err) {
      console.log("The following error occured: " + err.name)
    });
} else {
  console.log("getUserMedia not supported");
}
  function startRecording(button) {
    recorder && recorder.record();
    button.disabled = true;
    button.nextElementSibling.disabled = false;    
    micMeter();
    __log('Recording...'); 
    $('.msg').text('Recording has started. Please speak. Press Stop when finished.');
    
  }

  function stopRecording(button) { 
    recorder && recorder.stop();
    button.disabled = false;
    document.getElementById('strBtn').disabled = true;
    document.getElementById('recBtn').disabled = false;
    //button.previousElementSibling.disabled = false;
    micMeter(true);
    __log('Stopped recording.');
    $('.msg').text('Saving, Please Wait');
    // create WAV download link using audio data blob
    createDownloadLink();    
    recorder.clear();
  } 

  function micMeter(bVal) {
    if (bVal !== undefined && bVal == true) {
      //audio_context.suspend();
      document.getElementById('meter').style.display = 'none';
      return;
    } else {
      //audio_context.resume();
      document.getElementById('meter').style.display = '';
    }
    if (navigator.getUserMedia) {
      navigator.getUserMedia({
          audio: true
        },
        function(stream) {
          audioContext = audio_context;//new AudioContext();
          analyser = audioContext.createAnalyser();
          microphone = audioContext.createMediaStreamSource(stream);
          javascriptNode = audioContext.createScriptProcessor(2048, 1, 1);
    
          analyser.smoothingTimeConstant = 0.8;
          analyser.fftSize = 1024;
    
          microphone.connect(analyser);
          analyser.connect(javascriptNode);
          javascriptNode.connect(audioContext.destination);
    
          canvasContext = $("#canvas")[0].getContext("2d");
    
          javascriptNode.onaudioprocess = function() {
              var array = new Uint8Array(analyser.frequencyBinCount);
              analyser.getByteFrequencyData(array);
              var values = 0;
    
              var length = array.length;
              for (var i = 0; i < length; i++) {
                values += (array[i]);
              }
    
              var average = values / length;
    
    //          console.log(Math.round(average - 40));
    
              canvasContext.clearRect(0, 0, 20, 40);
              canvasContext.fillStyle = '#BadA55';
              canvasContext.fillRect(0, 40 - average, 20, 40);
              canvasContext.fillStyle = '#262626';
              //canvasContext.font = "18px impact";
              //canvasContext.fillText(Math.round(average - 40), -2, 50);
              //document.getElementById('strLevel').innerText = Math.round(average - 40);
    
            } // end fn stream
        },
        function(err) {
          console.log("The following error occured: " + err.name)
        });
    } else {
      console.log("getUserMedia not supported");
    }
  }

  function showModal() {
    $( "#myModal" ).show();
  }
  function hideModal() {
    $( "#myModal" ).hide();
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
      __log('Audio context set up.');
      __log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
    } catch (e) {
      alert('No web audio support in this browser!');
    }

    navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
      __log('No live audio input: ' + e);
    }); 
  };
 
