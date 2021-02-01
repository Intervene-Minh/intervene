importScripts('libmp3lame.js');
var mp3codec, data=[];    
  

this.onmessage = function(e){
  switch(e.data.command){
    case 'init':
      init(e.data.config);
      break;
    case 'record':
      record(e.data.buffer);
      break;
    case 'exportMP3':
      exportMP3();
      break;
  }
};

function init(config){
  mp3codec = Lame.init();
  Lame.set_mode(mp3codec, 0);
  // Mode 1 = joint stereo
  // Mode 2 = dual mono
  // Mode 3 = mono
  // Mode 4 = stereo
  Lame.set_num_channels(mp3codec, 3);
  Lame.set_in_samplerate(mp3codec, 48000);
  Lame.set_out_samplerate(mp3codec, 48000);
  //Lame.set_out_samplerate(mp3codec, config.sampleRate || 48000);
  Lame.set_bitrate(mp3codec, 128);
  Lame.init_params(mp3codec);
}

function record(ib){
   var buf = Lame.encode_buffer_ieee_float(mp3codec,  ib[1], ib[0]);
   var len = buf.size;
   for(var i=0;i<len;i++)	data.push(buf.data[i]);
}

function exportMP3(){
	var audioBlob = new Blob([new Uint8Array(data)], { type: 'audio/mp3' });
	this.postMessage(audioBlob);  
	Lame.encode_flush(mp3codec);
	Lame.close(mp3codec);
	mp3codec = null;
    data=[];
}
