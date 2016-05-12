$(document).ready(function () {
	
	// ------------------ check for support of geolocation
	var options = {
		enableHighAccuracy: true,
		timeout: 10000,
		maximumAge: 0
	};
	
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

	if ("geolocation" in navigator) {
		console.log('Get geolocation info');
		navigator.geolocation.getCurrentPosition(setPosition, showError, options);
	} else {
		//$('#geolocation').val(0);
	}
	
	if ( tz = jstz.determine() ) {
		console.log(tz.name());
		$('#timezone').val(tz.name());
	}
	
});