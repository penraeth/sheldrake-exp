<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StaringExperiment */

$this->title = $experiment->name;
//$this->params['breadcrumbs'][] = ['label' => 'Staring Experiment', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@exp/js/staring.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('//cdn.temasys.com.sg/skylink/skylinkjs/0.6.x/skylink.complete.min.js');
$this->registerCssFile('@exp/css/staring.css', ['depends' => [\yii\bootstrap\BootstrapAsset::className()]]);

?>

<script language="javascript">
	var userId=<?=Yii::$app->user->identity->id; ?>;
	var expId=<?=$experiment->id; ?>;
	var apiKey='<?=$experiment->apiKey; ?>';
	var isSubject=<?=$isSubject; ?>;
	var userName='<?=Yii::$app->user->identity->first_name . " ". Yii::$app->user->identity->last_name; ?>';
	var userEmail='<?=Yii::$app->user->identity->email; ?>';
	var observers=<?=$observers; ?>;
	var exitURL='<?=Url::to(['staring-experiment/view', 'id' => $experiment->id]); ?>';
	var expURL='<?=Url::to(['staring-experiment/experiment', 'id' => $experiment->id]); ?>';
	var showError=<?php isset($_GET['error']) ? print 'true' : print 'false'; ?>;
</script>

<?php if ($isSubject=='true'): ?>

	<div id="waitingScreen">
		<h2 class="mainTitle">
			Waiting Room for <?=$experiment->name; ?>
		</h2>
		
		<div id="showError" class="alert alert-danger">
			<b>Sorry, one of the participants has left or lost their connection</b>
			<p>
				You may continue with the experiment, picking up where you left off, once everyone has reconnected.
			</p>
		</div>
		
		<ol>
			<li> Wait for your partners to appear & check your video below.
			<li> Close other open applications to improve your computer's performance.
			<li> Click the <i>Begin Experiment</i> button when ready.
			<li> For each trial we randomly determine if you will be seen.
			<li> You'll be asked <i>Are you being stared at?</i> and may answer <i>yes</i> or <i>no</i> at any time.
			<li> Half the time you'll receive feedback about your guess.
			<li> After 20 trials you're done: a report will detail your results.
		</ol>
		
		<h4>Testing Video: <span id="status"></span></h4>
		<p clear="both">
			Below you should see your own video; make sure your face is well lit and centered. As others join the experiment you'll see them appear. During the experiment do not leave this page or refresh your browser. Importantly, refrain from communicating with others in your party.
		</p>
		<div id="peerList">
			<ul class="list-group">
			</ul>
		</div>
			
		<p>
			<a id="beginExperiment" class="btn btn-default" alt="Begin Experiment" href="" target="_blank">Begin Experiment</a>
		</p>
		
		
	</div>
	
<?php else: ?>

	<div id="waitingScreen">
		<h2 class="mainTitle">
			Waiting Room for <?=$experiment->name; ?> 
		</h2>
		
		<div id="showError" class="alert alert-danger">
			<b>Sorry, one of the participants has left or lost their connection</b>
			<p>
				You may continue with the experiment, picking up where you left off, once everyone has reconnected.
			</p>
		</div>
		
		<ol>
			<li> Wait for your partners to appear & check your video below.
			<li> Close other open applications to improve your computer's performance.
			<li> The subject begins the experiment once everyone's ready.
			<li> For each trial we randomly determine if you will see the subject or a random image.
			<li> While visible, stare intently at the subject and avoid distractions.
			<li> At anytime the subject may guess if they're being stared at, ending the trial.
			<li> After 20 trials you're done: a report will detail your results.
		</ol>
		
		<h4>Testing Video: <span id="status"></span></h4>
		<p clear="both">
			Below you should see the subject's video and that of other observers (if any) as they join the experiment. During the experiment, do not leave this page or refresh your browser. Importantly, refrain from communicating with others in your party.
		</p>
	</div>
	
	<div id="waitingOnSubject">
		<h4>Please wait for the subject to join the experiment...</h4>
	</div>
	
<?php endif; ?>


	<div id="trialContainer">
		<p id="trialInformation">
			<span style="margin:0 25px 0 0">Trial <b><span id="currentTrial"></span></b> of <span id="totalTrials"></span></span> Countdown: <b><span class="countdown"></span></b>
		</p>
	
		<div id="subjectDeterimation">
			<b>Are you being stared at?</b> 
			<a id="yes" class="btn btn-default" alt="Yes" href="" target="_blank">Yes</a>
			<a id="no" class="btn btn-default" alt="No" href="" target="_blank">No</a>
			<p>
				Please answer in the next <span class="countdown"></span> seconds.
			</p>
		</div>
	</div>
	
	<div id="videoContainer">
		<video id="selftest" class="selfVideo" autoplay muted></video>
		<span id="subjectVideo"></span>
		<span id="peerVideo"></span>
	</div>
	