<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StaringExperiment */

$this->title = $experiment->name;
//$this->params['breadcrumbs'][] = ['label' => 'Staring Experiment', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@exp/js/staring.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('//cdn.temasys.com.sg/skylink/skylinkjs/0.6.12/skylink.complete.min.js');
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
	<?php if isset($_GET['error']) {
			print 'var showError=true';
		} else { print 'var showError=false';
		}
	?>;
</script>

<?php if ($isSubject): ?>

	<div id="waitingScreen">
		<h3>
			Testing Video: <span id="status"></span>
		</h3>
		<p clear="both">
			Below you should see your own video feed; make sure your face is well lit and centered. As others join the experiment, you'll see them appear. <b>During the experiment, do not leave this page or refresh your browser. Also refrain from communicating with others in your party.</b>
		</p>
		<ol>
			<li> Once everyone has joined, begin the experiment.
			<li> For each trial we randomly determine if the observers see you.
			<li> Keep your attention on the screen and avoid distractions.
			<li> After a few seconds, you'll be asked to determine whether you're being stared at, then the next trial begins.
			<li> At the end of all trials you'll see the results of the experiment.
		</ol>
		
		<h4>Participants</h4>
		<div id="peerList">
			<ul class="list-group">
			</ul>
		</div>
		
		<div id="showError" class="alert alert-danger">
			<b>Sorry, one of the participants has left or lost their connection</b>
			<p>
				You may continue, picking up where you left off. For best results, we highly recommend using the latest version of either <a href="https://www.mozilla.org/en-US/firefox/new/" target="_blank">Firefox</a> or <a href="https://www.google.com/chrome/browser/" target="_blank">Chrome</a>. 
			</p>
		</div>
			
		<p>
			<a id="beginExperiment" class="btn btn-default" alt="Begin Experiment" href="" target="_blank">Begin Experiment</a>
		</p>
		
	</div>
	
<?php else: ?>

	<div id="waitingScreen">
		<h3>
			Testing Video: <span id="status"></span>
		</h3>
		<p clear="both">
			Below you should see the subject's video feed and that of other observers (if any) as they join the experiment. <b>During the experiment, do not leave this page or refresh your browser. Also refrain from communicating with others in your party.</b>
		</p>
		<ol>
			<li> Once everyone has joined, the subject will begin the experiment.
			<li> For each trial we randomly determine if you will see the subject.
			<li> While visible, stare intently at the subject and avoid distractions.
			<li> After a few seconds, the subject will determine whether you're staring at them, then the next trial begins.
			<li> At the end of all trials you'll see the results of this experiment.
		</ol>
		
		<div id="showError" class="alert alert-danger">
			<b>Sorry, one of the participants has left or lost their connection</b>
			<p>
				You may continue, picking up where you left off. For best results, we highly recommend using the latest version of either <a href="https://www.mozilla.org/en-US/firefox/new/" target="_blank">Firefox</a> or <a href="https://www.google.com/chrome/browser/" target="_blank">Chrome</a>.
			</p>
		</div>
	</div>
	
	<div id="waitingOnSubject">
		<b>Please wait for the subject to join the experiment...</b>
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
	