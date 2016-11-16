var debug = true;
var roomName = String(expId) + apiKey;
var totalTrials = 20;
var currentTrial = 0;
var trialDuration = 15;
var showQuestionAt = 15;
var userType = isSubject ? "subject" : "observer";
var isObserver = isSubject ? false : true;
var subjectPeerId = false;
var observerStarted = false;
var hideSubject = true;
var experimentInProgress = false;

var peerData = [];

if (showDropoutError){
	$('#dropoutError').show();
}


//-------------------------------------- skylink

	var skylink = new Skylink();
	skylink.setLogLevel(1);
	
	skylink.on('incomingMessage', function(message, peerId, peerInfo, isSelf) {
		if (isSelf) return;
		
		var mytime=new Date().getTime();
		debugmessage("subject sent message ------------- " + message.content + " " + mytime );
		
		// if subject is sending message
		if (peerInfo.userData.user == "subject") {
			if (message.content == "done") { // If the experiment is over
				skylink.leaveRoom();
				location.href = exitURL;
			} else if (Number(message.content) > currentTrial) { // If a new trial has begin
				if (!observerStarted) {
					// skylink.muteStream({ videoMuted: true, audioMuted: true });
					trialDisplaySettings();
					sizeVideos();
					observerStarted=true;
				}
				hideSubject=peerInfo.mediaStatus.videoMuted;
				currentTrial=Number(message.content);
				observer_startTrial();
			}
		}
	});
	
	skylink.on('peerJoined', function(peerId, peerInfo, isSelf) {
		if (isSubject) {
			peerData[peerId] = peerInfo;
			//peerData[peerId].userData.status='ready';
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
			setTimeout(location.href = expURL+'?error=true', 500);
		}
		
		// remove from viewable list of participants
		if (isSubject) {
			if (experimentInProgress){
				setTimeout(location.href = expURL +'?error=true', 500);
			} else {
				delete peerData[peerId];
				checkBeginSubject();
			}
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
		
		userData = skylink.getUserData();
		userData.status = 'ready';
		skylink.setUserData(userData);
		if (isSubject) {
			peerData[peerId].userData.status='ready';
		}
	});
	
	skylink.on('mediaAccessSuccess', function(stream) {
		var vid = $('#selftest')[0];
		attachMediaStream(vid, stream);
	});
	
	skylink.init({
		apiKey: 'c919c00a-7e85-4ef7-9942-626a45487a11'
	}, function(error, success) {
		if (error) {
			$('#status').html('Failed retrieval for room information ' + (error.error.message || error.error));
		} else {
		
		skylink.joinRoom(roomName, {
			userData: {
				user: userType,
				userId: userId,
				experiment: expId,
				trial: currentTrial,
				name: userName,
				email: userEmail,
				observers: observers,
				status: 'unknown'
			},
			audio: true,
			video: true,
			bandwidth: {
				video:500,
				audio:50
			}
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
		beginExperiment();
	});
	
	function beginExperiment() {
		debugmessage("Beginning experiment " + roomName);
		startExperiment();
		callApi('getNextTrial');
		trialDisplaySettings();
		skylink.lockRoom();
	}
	
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
		debugmessage("New trial: " + startTime + " hide subject? " + hideSubject);

		$('#currentTrial').html(currentTrial);
		$("#trialInformation").css("text-shadow","0px 1px #fff, 0px 2px 3px #fff, 0px 3px 4px #fff, -2px -2px 4px #fff");
		
		if (hideSubject) {
			// hide video and display distraction
			debugmessage("hiding subject");
			$("#subjectVideo").hide();
			$("#observerCommand").hide();
			var imgId=Math.floor(Math.random() * 30) + 1;
			var imageUrl="/exp/images/staring/"+imgId+".jpg";
			$('.wrap').css({'background-image': 'url(' + imageUrl + ')', });
		} else {
			// show video
			debugmessage("showing subject");
			$('.wrap').css('background-image', '');
			$("#subjectVideo").show();
			$("#observerCommand").show();
		}
		
		trialTime=0;
		
		try{ clearTimeout(timerId); } catch(err){}
		observer_displayCountdown();
	}
	
	function observer_displayCountdown() {
		var currentTime = new Date().getTime(); // Get current time.  
		var countdown = Math.ceil((trialDuration) - ((currentTime - startTime) /1000));
		$('.countdown').html(countdown);
		
		if (countdown <= 0) {  // finished
			debugmessage("trial over");
			clearTimeout(timerId);
		} else { // still going
			var processingDelay = (currentTime - startTime) - trialTime;
			//debugmessage("countdown: " + countdown + " adjustment:" + processingDelay);
			trialTime+=1000;
			timerId = setTimeout(observer_displayCountdown, (1000-processingDelay));
		}
	}

	
	
//-------------------------------------- subject trial handling

	function subject_startTrial() {
		$('#currentTrial').html(currentTrial);
		$('.wrap').addClass('animateBackground');
		$('#subjectDeterimation p').html('Please answer in the next <span class="countdown"></span> seconds.');
		
		// show or hide video
		showVideo = Boolean(Math.round(Math.random()));
		debugmessage("showVideo Boolean: " + showVideo);
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
		feedback=Math.round(Math.random());
		subject_displayCountdown();
	}
	
	function subject_displayCountdown() {
		var currentTime = new Date().getTime(); // Get current time.  
		var countdown = Math.ceil((trialDuration) - ((currentTime - startTime) /1000));
		$('.countdown').html(countdown);
		
		if (countdown <= 0) {
			$('#subjectDeterimation p').html('Trial over: <i>Please Answer</i>');
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
		$('.wrap').removeClass('animateBackground');
		debugmessage("ending trial");
		if (feedback == 1) {
			var message= showVideo ? "You were STARED AT" : "You were NOT stared at"
			if ((showVideo && judgment == 1) || (!showVideo && judgment == 0)) {
				$('#subjectDeterimation p').html('<span class="correct">' +message+ '</span>');
			} else {
				$('#subjectDeterimation p').html('<span class="incorrect">' +message+ '</span>');
			}
		} else {
				$('#subjectDeterimation p').html('<span class="nofeedback">No feedback this time</span>');
		}
		setTimeout(
			function(){
				$('#subjectDeterimation').hide();
				logTrial(judgment);
			}, 2000);
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
			debugmessage("participant joining: " + peerInfo.userData.user + ", status: " + peerInfo.userData.status);
			if (peerInfo.userData.user == 'subject') {
				itemClass = 'success';
			} else if (peerInfo.userData.user != 'subject'  &&  peerInfo.userData.status == 'ready') {
				readyCount++;
				itemClass = 'success';
			}
			$('#peerList ul').append('<li class="list-group-item list-group-item-'+itemClass+' peerListItem">'+peerInfo.userData.name+' ('+peerInfo.userData.email+')');
		}
		
		if (readyCount > 0) {
			// now that we have at least one observer, show the begin button and start the clock on pre-experiment chat
			$('#beginExperiment').show();
			if(readyCount==totalParticipants-1) {
				setTimeout(autoStartCountdown, 90000);
			}
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

	var autoStartTime = 0;
	var autoStartDuration = 0;
	var autoStartCountdownDuration = 30;
	
	function autoStartCountdown() {
		$('#autoStartWarning').show();
		if (autoStartTime==0) { autoStartTime = new Date().getTime(); }
		var currentTime = new Date().getTime(); // Get current time.  
		var autoStartCount = Math.ceil((autoStartCountdownDuration) - ((currentTime - autoStartTime) /1000));
		$('.autoStartCountdown').html(autoStartCount);
		
		if (autoStartCount <= 1) {
			$('#autoStartWarning').hide();
			beginExperiment();
		} else { // still going
			var processingDelay = (currentTime - autoStartTime) - autoStartDuration;
			debugmessage("autostart countdown: " + autoStartCount + " adjustment:" + processingDelay);
			autoStartDuration+=1000;
			setTimeout(autoStartCountdown, (1000-processingDelay));
		}
	}

	
	function countObservers() {
		totalObservers = 0;
		participantIds = [];
		for (peerId in peerData) {
			peerInfo = peerData[peerId];
			totalObservers+=peerInfo.userData.observers;
			participantIds.push(peerInfo.userData.userId);
		}
		debugmessage("observers present: " + totalObservers + ", participant ids: " + participantIds.join());
	}
	
	function sizeVideos() {
		var numberOfVideos = $('#videoContainer video').length + $('#videoContainer object').length;
		var windowWidth = $('#videoContainer').width();
		var newWidth = (windowWidth / numberOfVideos)-50;
		$('#videoContainer video').width(newWidth);
		$('#videoContainer object').width(newWidth);
		$('#videoContainer video').height(newWidth*.7);
		$('#videoContainer object').height(newWidth*.7);
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
			experimentInProgress=true;
		},
		completeExperiment: function(data, status) {
			skylink.leaveRoom();
			location.href = exitURL;
		},
		getNextTrial: function(data, status) {
			currentTrial = data.next;
			if (isSubject) {
				subject_startTrial();
			}
		},
		logTrial: function(data, status) {
			if (currentTrial==totalTrials) { // end of experiment
				callApi('completeExperiment');
				sendMessageAll("done");
			} else { // continue
				callApi('getNextTrial');
			}
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
		debugmessage("logging trial with: " + totalObservers + " observers, " + feedback + " feedback and a judgement of " + judgment);
		apiData = new Array();
		apiData['trial'] = currentTrial;
		apiData['observers'] = showVideo ? totalObservers : 0 ;
		apiData['judgment'] = judgment;
		apiData['feedback'] = feedback;
		
		callApi('logTrial', apiData);
	}
	
	
	// prepare data for start experiment call
	function startExperiment() {
		countObservers();
		apiData = new Array();
		apiData['participantIds'] = participantIds.join();
		
		callApi('startExperiment', apiData);
	}
	
