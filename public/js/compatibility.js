$(document).ready(function () {
/*
	// check for getUserMedia support
	navigator.MediaDevices.getUserMedia || (navigator.MediaDevices.getUserMedia = navigator.mozGetUserMedia || navigator.webkitGetUserMedia || navigator.msGetUserMedia);
		 
	if (navigator.MediaDevices.getUserMedia) {
		navigator.MediaDevices.getUserMedia({
		  video: true
		}, onSuccess, onError);
	} else {
		$('#compatibility').val(0);
		console.debug('Compatibility : ' + $('#compatibility').val());
	}
	 
	function onSuccess() {
		$('#compatibility').val(1);
		console.debug('Compatibility : ' + $('#compatibility').val());
	}
	 
	function onError() {
		$('#compatibility').val(2);
		console.debug('Compatibility : ' + $('#compatibility').val());
	}
	
	var promisifiedOldGUM = function(constraints, successCallback, errorCallback) {
	
		// First get ahold of getUserMedia, if present
		var getUserMedia = (navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia);
	
		// Some browsers just don't implement it - return a rejected promise with an error
		// to keep a consistent interface
		if(!getUserMedia) {
			$('#compatibility').val(0);
			return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
		}
	
		// Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
		return new Promise(function(successCallback, errorCallback) {
			getUserMedia.call(navigator, constraints, successCallback, errorCallback);
	  });
			
	}

	// Older browsers might not implement mediaDevices at all, so we set an empty object first
	if(navigator.mediaDevices === undefined) {
		navigator.mediaDevices = {};
	}
	
	// Some browsers partially implement mediaDevices. We can't just assign an object
	// with getUserMedia as it would overwrite existing properties.
	// Here, we will just add the getUserMedia property if it's missing.
	if(navigator.mediaDevices.getUserMedia === undefined) {
		navigator.mediaDevices.getUserMedia = promisifiedOldGUM;
	}
		
	navigator.mediaDevices.getUserMedia({video: true})
	.then(function(stream) {
		$('#compatibility').val(1);
		console.debug('Compatibility : ' + $('#compatibility').val());
		var video = document.querySelector('video');
		video.src = window.URL.createObjectURL(stream);
		video.onloadedmetadata = function(e) {
			//	video.play();
			//stopWebCam();
		};
	})
	.catch(function(err) {
		$('#compatibility').val(2);
		console.debug(err.name + ": " + err.message);
	});
	

	function stopWebCam() {
		if (video) {
			video.pause();
			video.src = '';
			video.load();
		}
	
		if (cameraStream && cameraStream.stop) {
			cameraStream.stop();
		}
		stream = null;
	}
	*/


	// Here's a simple example on how you can start using Skylink
  var SkylinkDemo = new Skylink();

  // Subscribe all events first before init()
  SkylinkDemo.on("incomingStream", function (peerId, stream, peerInfo, isSelf) {
    if (isSelf) {
      attachMediaStream(document.getElementById("selfVideo"), stream);
    } else {
      var peerVideo = document.createElement("video");
      peerVideo.id = peerId;
      peerVideo.autoplay = "autoplay";
      document.getElementById("peersVideo").appendChild(peerVideo);
      attachMediaStream(peerVideo, stream);
    }
  });

  SkylinkDemo.on("peerLeft", function (peerId, peerInfo, isSelf) {
    if (!isSelf) {
      var peerVideo = document.getElementById(peerId);
      // do a check if peerVideo exists first
      if (peerVideo) {
        document.getElementById("peersVideo").removeChild(peerVideo);
      } else {
        console.error("Peer video for " + peerId + " is not found.");
      }
    }
  });

 // never call joinRoom in readyStateChange event subscription.
 // call joinRoom after init() callback if you want to joinRoom instantly.
 SkylinkDemo.on("readyStateChange", function (state, room) {
   console.log("Room (" + room + ") state: ", room);
 })

 // always remember to call init()
 SkylinkDemo.init("10185b27-a4cd-4ba0-a03f-e9bea4b5b67f", function (error, success) {
   // do a check for error or success
   if (error) {
     console.error("Init failed: ", error);
   } else {
     SkylinkDemo.joinRoom("my_room", {
       userData: "My Username",
       audio: true,
       video: true
     });
   }
 });
	

	
	// ------------------ check for support of geolocation
	var options = {
		enableHighAccuracy: true,
		timeout: 10000,
		maximumAge: 0
	};

	if ("geolocation" in navigator) {
		navigator.geolocation.getCurrentPosition(setPosition, showError, options);
	} else {
		$('#geolocation').val(0);
	}
	
	// set latitude and longitude
	function setPosition(pos) {
	
		var crd = pos.coords;
		console.debug('Your current position is:');
		console.debug('Latitude : ' + crd.latitude);
		console.debug('Longitude: ' + crd.longitude);
		console.debug('More or less ' + crd.accuracy + ' meters.');
  
		$('#latitude').val(crd.latitude);
		$('#longitude').val(crd.longitude);
	}
	
	function showError(error) {
		$('#geolocation').val(0);
		switch(error.code) {
			case error.PERMISSION_DENIED:
				console.debug("User denied the request for Geolocation.");
				break;
			case error.POSITION_UNAVAILABLE:
				console.debug("Location information is unavailable.");
				break;
			case error.TIMEOUT:
				console.debug("The request to get user location timed out.");
				break;
			case error.UNKNOWN_ERROR:
				console.debug("An unknown error occurred.");
				break;
		}
	}
	

});