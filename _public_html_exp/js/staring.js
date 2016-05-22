var debug = true;
var roomName = String(expId) + apiKey;
var totalTrials = 20;
var currentTrial = 0;
var trialDuration = 15;
var showQuestionAt = 7;
var userType = isSubject ? "subject" : "observer";
var isObserver = isSubject ? false : true;
var subjectPeerId = false;
var observerStarted = false;
var showSubject = false;

var peerData = [];


//-------------------------------------- skylink

	var skylink = new Skylink();
	skylink.setLogLevel(1);
	
	skylink.on('incomingMessage', function(message, peerId, peerInfo, isSelf) {
		if (isSelf) return;
		
		var mytime=new Date().getTime();
		debugmessage("subject sent message ------------- " + message.content + " " + mytime );
		// if peer is subject and a new trial has begin
		if (peerInfo.userData.user == "subject" && Number(message.content) > currentTrial) {
			if (!observerStarted) {
				skylink.muteStream({ videoMuted: true, audioMuted: true });
				trialDisplaySettings();
				sizeVideos();
				observerStarted=true;
			}
			showSubject=peerInfo.mediaStatus.videoMuted;
			callApi('getNextTrial');
		}
	});
	
	skylink.on('peerJoined', function(peerId, peerInfo, isSelf) {
		if (isSubject) {
			peerData[peerId] = peerInfo;
			checkBeginSubject();
		}
		if (isSelf) return; // We already have a video element for our video and don't need to create a new one.
		
		var vid = document.createElement('video');
		vid.autoplay = true;
		vid.id = 'video_'+peerId;
		
		if(isObserver  &&  peerInfo.userData.user == "subject") {
			$('#subjectVideo').append(vid);
			subjectPeerId = peerId;
			checkBeginObserver();
		} else {
			$('#peerVideo').append(vid);
		}
	});
	
	skylink.on('peerLeft', function(peerId, peerInfo, isSelf) {
		if (isSelf) return;
		
		// remove video element
		$('#video_'+peerId).remove();

		// host is gone; return to main room
		if (isObserver  &&  peerInfo.userData.user == "subject") {
			setTimeout(window.location.reload(), 500);
		}
		
		// remove from viewable list of participants
		if (isSubject) {
			delete peerData[peerId];
			checkBeginSubject();
		}
	});
	
	skylink.on('peerUpdated', function(peerId, peerInfo, isSelf) {
		if (isSelf) return;
		if (isSubject) {
			checkBeginSubject();
		}
	});
	
	
	skylink.on('incomingStream', function(peerId, stream, isSelf) {
		if (isSelf) return;
		var vid = document.getElementById('video_'+peerId);
		attachMediaStream(vid, stream);
		sizeVideos();
		
		if (isObserver  &&  peerId == subjectPeerId) {
			userData = skylink.getUserData();
			userData.status = 'ready';
			skylink.setUserData(userData);
		}		
	});
	
	skylink.on('mediaAccessSuccess', function(stream) {
		var vid = $('#selftest')[0];
		attachMediaStream(vid, stream);
	});
	
	skylink.init({
		apiKey: '10185b27-a4cd-4ba0-a03f-e9bea4b5b67f'
	}, function(error, success) {
		if (error) {
			$('#status').html('Failed retrieval for room information ' + (error.error.message || error.error));
		} else {
		
		skylink.joinRoom(roomName, {
			userData: {
				user: userType,
				experiment: expId,
				trial: currentTrial,
				name: userName,
				email: userEmail,
				observers: observers,
				status: 'unknown'
			},
			audio: true,
			video: true
		}, function(error, success) {
			if (error) {
				$('#status').html(error.error.message || error.error);
			} else {
				$('#videoContainer').show();
				$('#status').html('Ready');
			}
		});
	  }
	});


//-------------------------------------- button controls

	$('#beginExperiment').bind('click', function(e){
		e.preventDefault();
		debugmessage("Beginning experiment " + roomName);
		callApi('startExperiment');
		callApi('getNextTrial');
		trialDisplaySettings();
		skylink.lockRoom();
	});
	
	$('#yes').bind('click', function(e){
		e.preventDefault();
		endTrial(1);
	});
	
	$('#no').bind('click', function(e){
		e.preventDefault();
		endTrial(0);
	});
	
	$('#exit').bind('click', function(e){
		try{ clearTimeout(timerId); } catch(err){}
		$('.wrap').removeClass('animateBackground');
		skylink.leaveRoom();
	});
	
	
//-------------------------------------- observer trial handling
	
	function observer_startTrial() {
		startTime = new Date().getTime();

		//currentTrial++;
		$('#currentTrial').html(currentTrial);
		$('.wrap').removeClass('animateBackground');
		
		if (showSubject) {
			// hide video and display distraction
			$("#subjectVideo").hide();
			$('.wrap').addClass('animateBackground');
		} else {
			// show video
			$("#subjectVideo").show();
		}
		
		debugmessage("New trial: " + startTime + " subject visible? " + showSubject);
		
		trialTime=0;
		
		try{ clearTimeout(timerId); } catch(err){}
		observer_displayCountdown();
	}
	
	function observer_displayCountdown() {
		var currentTime = new Date().getTime(); // Get current time.  
		var countdown = Math.ceil((trialDuration) - ((currentTime - startTime) /1000));
		$('.countdown').html(countdown);
		
		if (countdown <= 0) {  // finished
			clearTimeout(timerId);
			if (currentTrial==totalTrials) { // end of experiment
				skylink.leaveRoom();
				location.href = exitURL;
			}
		} else { // still going
			var processingDelay = (currentTime - startTime) - trialTime;
			//debugmessage("countdown: " + countdown + " adjustment:" + processingDelay);
			trialTime+=1000;
			timerId = setTimeout(observer_displayCountdown, (1000-processingDelay));
		}
	}

	
	
//-------------------------------------- subject trial handling

	function subject_startTrial() {
		
		//currentTrial++;
		$('#currentTrial').html(currentTrial);
		$('.wrap').addClass('animateBackground');
		
		// show or hide video
		showVideo = Boolean(Math.round(Math.random()));
		if (showVideo) {
			skylink.muteStream({ videoMuted: false, audioMuted: true });
		} else {
			skylink.muteStream({ videoMuted: true, audioMuted: true });
		}
		
		// observers determine start of trial based on the below; important that it happens after muteStream above
		sendMessageAll(String(currentTrial));
		startTime = new Date().getTime();
		debugmessage("New trial: " + startTime);
		
		trialTime=0;
		subject_displayCountdown();
	}
	
	function subject_displayCountdown() {
		var currentTime = new Date().getTime(); // Get current time.  
		var countdown = Math.ceil((trialDuration) - ((currentTime - startTime) /1000));
		$('.countdown').html(countdown);
		
		if (countdown <= 0) { // finished before answering, mark as pass
			endTrial(3);
		} else { // still going
			if (countdown == showQuestionAt) {
				$('#subjectDeterimation').show(); // are you being stared at?
			}
			var processingDelay = (currentTime - startTime) - trialTime;
			//debugmessage("countdown: " + countdown + " adjustment:" + processingDelay);
			trialTime+=1000;
			timerId = setTimeout(subject_displayCountdown, (1000-processingDelay));
		}
	}
	
	function endTrial(judgment) {
		try{ clearTimeout(timerId); } catch(err){}
		$('.wrap').removeClass('animateBackground');
		$('#subjectDeterimation').hide();
		
		// update database
		logTrial(judgment);
		
		if (currentTrial==totalTrials) { // end of experiment
			callApi('completeExperiment');
		} else { // continue
			callApi('getNextTrial');
		}
	}
	


//-------------------------------------- Helper functions
	
	function debugmessage(message) {
		if (debug && typeof console != "undefined") {
			if (typeof console.debug != "undefined") {
				console.debug(message);
			}
			console.log(message);
		}
	}
	
	function sendMessageAll(msg) {
		if (isSubject) {
			for (peerId in peerData) {
				debugmessage('sendMessageAll:'+peerId + " trial:" + msg);
				skylink.sendMessage(msg, peerId);
			}
		}
	}
	
	function checkBeginSubject() {
		$('.peerListItem').remove();
		readyCount = 0;
		for (peerId in peerData) {
			peerInfo = peerData[peerId];
			itemClass = 'warning';
			if (peerInfo.userData.user == 'subject') {
				itemClass = 'success';
			} else if (peerInfo.userData.user != 'subject'  &&  peerInfo.userData.status == 'ready') {
				readyCount++;
				itemClass = 'success';
			}
			$('#peerList ul').append('<li class="list-group-item list-group-item-'+itemClass+' peerListItem">'+peerInfo.userData.name+' ('+peerInfo.userData.email+')');
		}
		
		if (readyCount > 0) {
			$('#beginExperiment').show();
		} else {
			$('#beginExperiment').hide();
		}
	}
	
	function checkBeginObserver() {
		if (subjectPeerId) {
			$('#waitingOnSubject').hide();
		} else {
			$('#waitingOnSubject').show();
		}
	}
	
	function countObservers() {
		totalObservers = 0;
		for (peerId in peerData) {
			peerInfo = peerData[peerId];
			totalObservers+=peerInfo.observers;
		}
	}
	
	function sizeVideos() {
		var numberOfVideos = $('#videoContainer video').length + $('#videoContainer object').length;
		var windowWidth = $('#videoContainer').width();
		var newWidth = (windowWidth / numberOfVideos)-50;
		$('#videoContainer video').width(newWidth);
		$('#videoContainer object').width(newWidth);
		$('#videoContainer video').height(newWidth*.7);
		$('#videoContainer object').height(newWidth*.7);
		console.debug("window=" + windowWidth + " new video width=" + newWidth);
	}
	
	function trialDisplaySettings() {
		$('#waitingScreen').empty();
		$('#selftest').remove();
		$('#peerVideo').empty();
		$('#trialContainer').show();
		$('#totalTrials').html(totalTrials);
	}


//-------------------------------------- API

	// these functions get called on successful completion of each API method
	var resultFunctions = {
		startExperiment: function(data, status) {
			// started
		},
		completeExperiment: function(data, status) {
			skylink.leaveRoom();
			location.href = exitURL;
		},
		getNextTrial: function(data, status) {
			currentTrial = data.next;
			debugmessage("api set current trial to " + currentTrial);
			if (isSubject) {
				subject_startTrial();
			} else {
				observer_startTrial();
			}
		},
		logTrial: function(data, status) {
			//currentTrial = data.next;
		}
	}
	
	
	// main API call
	function callApi(method, data) {
		urlMethod = method.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
		urlString = '/exp/api/'+urlMethod+'/'+expId+'/'+apiKey;
		
		fd = new FormData();
		for (key in data) {  fd.append(key, data[key]);  }
		
		$.ajax({
			type: 'POST',
			url: urlString,
			timeout: 5000,
			data: fd,
			error: function(xhr, status, error){
				debugmessage(xhr.status+': '+error);
			},
			success: function(data, status, xhr) {
				debugmessage(xhr.status+': '+data.message);
				if (resultFunctions[method]) {
					resultFunctions[method](data, status);
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
	}
	
	
	// prepare data for log call
	function logTrial(judgment) {
		countObservers();
		apiData = new Array();
		apiData['trial'] = currentTrial;
		apiData['observers'] = showVideo ? totalObservers : 0 ;
		apiData['judgment'] = judgment;
		
		callApi('logTrial', apiData);
	}
	