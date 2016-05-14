<script>
	var currentTrial = 1;

	// ----------->
	// make sure these are populated from the database
	var _expId = '240';
	var _apiKey = '13d85ced18c111e69fe1f23c9170f017';
	// ----------->

	
	// these functions get called on successful completion of each API method
	
	var resultFunctions = {
		startExperiment: function(data, status) {
			// started
		},
		completeExperiment: function(data, status) {
			// completed
		},
		getNextTrial: function(data, status) {
			currentTrial = data.next;
			$('#currentTrial').val(currentTrial);
		},
		logTrial: function(data, status) {
			currentTrial = data.next;
			$('#currentTrial').val(currentTrial);
		}
	}
	
	
	// main API call
	
	function callApi(method, data) {
		urlMethod = method.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
		urlString = '/exp/api/'+urlMethod+'/'+_expId+'/'+_apiKey;
		
		fd = new FormData();
		for (key in data) {  fd.append(key, data[key]);  }
		
		$.ajax({
			type: 'POST',
			url: urlString,
			timeout: 5000,
			data: fd,
			error: function(xhr, status, error){
				console.log(xhr.status+': '+error);
				$('#apiResult').val(xhr.status+"\n"+error);  // for debug only
			},
			success: function(data, status, xhr) {
				$('#apiResult').val(xhr.status+"\n"+data.message);  // for debug only
				console.log(xhr.status+': '+data.message);
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
	
	function logTrial() {
		apiData = new Array();
		apiData['trial'] = $('#currentTrial').val();
		
		// test data only; populate the actual array from other functions
		apiData['observers'] = 4;
		apiData['judgment'] = 3;
		
		callApi('logTrial', apiData);
	}
	
</script>



<div class="panel panel-default col-sm-6 col-sm-offset-3" style="padding:12px">
	<div class="col-sm-4">
		<p><a class="btn btn-sm btn-default" href="#" onClick="callApi('getNextTrial');">getNextTrial</a></p>
		<p><a class="btn btn-sm btn-default" href="#" onClick="callApi('startExperiment');">startExperiment</a></p>
		<p><a class="btn btn-sm btn-default" href="#" onClick="callApi('completeExperiment');">completeExperiment</a></p>
		<p><a class="btn btn-sm btn-default" href="#" onClick="logTrial();">logTrial</a></p>
	</div>
	<div class="col-sm-8">
		Trial: <input text="text" size="2" id="currentTrial"><br>
		API Result:<br>
		<textarea id="apiResult" rows="3" cols="32"></textarea><br>
	</div>
</div>
