navigator.mediaDevices.getUserMedia({ audio:false, video: true }).then((stream)=>{
	console.log(stream)
	
	let video = document.getElementById('video')
    video.srcObjet = stream
	
	
	
	}).catch((err)=>console.log(err))


/*
var p = navigator.mediaDevices.getUserMedia({ audio: false, video: true });

p.then(function(mediaStream) {
  var video = document.querySelector('video');
  video.src = window.URL.createObjectURL(mediaStream);
  video.onloadedmetadata = function(e) {
    // Do something with the video here.
  };
});

p.catch(function(err) { console.log(err.name); }); // always check for errors at the end.
*/