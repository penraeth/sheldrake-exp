<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StaringExperiment */

#$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Staring Experiments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$isHost = ($host->id == Yii::$app->user->identity->id);
?>

<style>
	.has-error input[type="text"].form-control { background-color: #fdd; }
	.right {
		color:green;
		font-weight:bold;
	}
	.wrong {
		color:red;
		font-weight:normal;
	}
	.statement {
	    background-color: #dff0d8;
		color: #468847;
		border-radius:4px;
		border: 1px solid #dddddd;
		padding: 10px 15px;
		margin: 0 0 2em 0;
	}
</style>

<div class="staring-experiment-view">


	<?php if ( $experiment->datecompleted ): ?>
		
		<!-- result statement -->
		<div class="col-sm-8 col-sm-offset-2">
			<p class="statement">
			<b>Experiment: <?= Html::encode($experiment->name) ?> Completed <?=$experiment->datecompleted ?></b><BR><BR>
			<?php 
				$right=0;
				$wrong=0;
				$fbRight=0;
				$fbWrong=0;
				$subject = ($isHost) ? "you" : $host->first_name;
				$subjectIs = ($isHost) ? "are" : "is";
				$subjectWas = ($isHost) ? "were" : "was";
				$observer = ($isHost) ? "the observers" : "you";
				
				foreach ($trials as $trial) {
	
					$observed = ($trial->observers == 0) ? 0 : 1;
					$feedback = $trial->feedback;
					if ($trial->judgment == $observed) {
						$right++;
						if ($feedback) { $fbRight++; }
					} else {
						$wrong++;
						if ($feedback) { $fbWrong++; }
					}
				}
					
				$totalTrials = $right + $wrong;
				$fbTotalTrials = $fbRight + $fbWrong;
				$accuracy = ($right > 0) ? round($right / $totalTrials * 100) : 0;
				$fbAccuracy = ($fbRight > 0) ? round($fbRight / $fbTotalTrials * 100) : 0;
				
				print "Of $totalTrials trials $subject guessed correctly <b>$right</b> times and incorrectly <b>$wrong</b> times, giving an overall accuracy rating of <b>$accuracy%</b>. With a 50% chance of guessing right, this is ";

				if ($accuracy >= 80){ 
					print "<i>extremely</i> high - significantly above chance - and strongly supports the hypothesis, that people can tell when they're stared at through a live video feed."; }
				elseif ($accuracy >= 70){ 
					print "<i>very</i> high - significantly above chance - and supports the hypothesis, that people can tell when they're stared at through a live video feed."; }
				elseif ($accuracy >= 60){
					print "well above the chance level and supports the hypothesis that people can tell when they're stared at through a live video feed."; }
				elseif ($accuracy > 50){
					print "slightly above chance. Keep trying!"; }
				
				elseif ($accuracy == 50){
					print "right at the chance level. Keep trying!"; }
					
				elseif ($accuracy <= 20){
					print "<i>extremely</i> low - significantly below chance - and may indicate that some people can disrupt another's ability to tell when they're being stared at."; }
				elseif ($accuracy <= 30){
					print "<i>very</i> low - significantly above chance - and may indicate that some people can disrupt another's ability to tell when they're being stared at."; }
				elseif ($accuracy <= 40){
					print "well below the chance level and may indicate that some people can disrupt another's ability to tell when they're being stared at."; }
				elseif ($accuracy < 50){
					print "slightly below chance. Keep trying!"; }

				print "<BR><BR>In $fbTotalTrials trials $subject $subjectWas told at the end whether anyone was staring. For these $subject $subjectWas correct $fbRight times and incorrect $fbWrong times, giving an accuracy of $fbAccuracy%.";
				
				if ($fbAccuracy > $accuracy){ 
					print " ". ucfirst($subject) ." did better <i>with</i> feedback."; }
				else {
					print " ". ucfirst($subject) ." did better <i>without</i> feedback."; }

			?>
			</p>
		</div>
		
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Trial Results</h3>
					</div>
					<div class="panel-body panel-table-data">
						<table class="table table-condensed">
							<tr>
								<td align="right"><b>trial</b></th>
								<td align="center"><b># of observers</b></th>
								<td align="center"><b>feedback</b></th>
								<td align="center"><b>subject guessed</b></th>
							</tr>
							<?php foreach ($trials as $trial): ?>
								<?php
									switch($trial->observers) {
										case 0: $observers = 'none'; $observed = 0; break;
										default: $observers = $trial->observers; $observed = 1;
									}
									if ($trial->judgment == $observed) {
										$class ='right';
										$judgment = 'right';
									} else {
										$class ='wrong';
										$judgment = 'wrong';
									}
									
									if ($trial->feedback == 1) {
										$feedback = 'yes';
									} else {
										$feedback = 'no';
									}
								?>
								<tr valign="middle">
									<td align="right" title="<?=$trial->created_at;?>"><?=$trial->trial;?></td>
									<td align="center"><?=$observers;?></td>
									<td align="center"><?=$feedback;?></td>
									<td align="center" class="<?=$class;?>"><?=$judgment;?></td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			
			</div>
		</div>
	
			
	<?php endif; ?>

		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Experiment: <?= Html::encode($experiment->name) ?></h3>
					</div>
					<div class="panel-body panel-table-data">
						<table class="table table-condensed">
							<tr valign="middle">
								<td>Subject</td>
								<td><?=$host->first_name;?> <?=$host->last_name;?></td>
							</tr>
							<tr valign="middle">
								<td>Created</td>
								<td><?=$experiment->created_at;?></td>
							</tr>
							<tr valign="middle">
								<td>Started</td>
								<td><?=($experiment->datestarted)?$experiment->datestarted:'pending';?></td>
							</tr>
							<tr valign="middle">
								<td>Completed</td>
								<td><?=($experiment->datecompleted)?$experiment->datecompleted:'pending';?></td>
							</tr>
						</table>
						<?php if ($isHost  &&  !$experiment->datecompleted): ?>
							<div style="padding-bottom:12px; padding-top:0px; text-align:right">
								<?php $form = ActiveForm::begin([
										'id' => 'form-participant',
										'action' => Url::To(['staring-participant/create'])
									]); ?>
									<input type="hidden" id="staringexperiment-id" name="StaringParticipant[exp_id]" value="<?=$experiment->id;?>">
									<input type="hidden" id="staringexperiment-id" name="StaringParticipant[observers]" value="0">
									<input type="hidden" id="staringexperiment-id" name="StaringParticipant[relationship]" value="0">
									<?= Html::submitButton('Enter Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>', ['class'=>'btn btn-info']) ?>
								<?php ActiveForm::end(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			
			</div>
		</div>
    
    <?php if ($isHost): ?>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Invitees</h3>
					</div>
					<div class="panel-body panel-table-data">
						<table class="table table-condensed">
							<tr>
								<th>E-mail</th>
								<th>Status</th>
							</tr>
							<?php foreach ($invitations as $invitation): ?>
								<?php
									switch($invitation->email_status) {
										case 1: $status = 'sent'; break;
										case -1: $status = 'failed'; break;
										default: $status = 'pending';
									}
								?>
								<tr valign="middle">
									<td><?=$invitation->email;?></td>
									<td><?=$status;?></td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			
			</div>
		</div>
	<?php endif; ?>
	
	
	<?php if ( !$experiment->datecompleted ): ?>
		<!-- active -->
		
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<?php if (!$isHost): ?>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Number of observers & relationship</h3>
						</div>
						<div class="panel-body">
							<?php $form = ActiveForm::begin([
									'id'		=> 'create-form',
									'action'	=> Url::To(['staring-participant/create']),
									'fieldConfig' => [
										'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}"
									]
								]); ?>
								<input type="hidden" id="staringexperiment-id" name="StaringParticipant[exp_id]" value="<?=$experiment->id;?>">
								<div class="row">
									<div class="col-sm-6">
										<?= $form->field($participant, "observers")
											->label('No. of observers, including yourself')
											->textInput(['value'=>'1']);
										?>
										If it's just you looking at the screen put 1, if another perosn is watching with you put 2, etc.
									</div>
									<div class="col-sm-6">
										<?= $form->field($participant, "relationship")
											->label('Your relationship to the subject')
											->dropDownList([
												'' => 'Select...',
												'1'	=> 'Close friend, partner or close family member',
												'2'	=> 'Friend, colleague or familiar person',
												'3' => 'Acquaintance or person seen infrequently',
												'4' => 'Never met before'
											]);
										?>
										If more are watching with you, select the closest relationship.
									</div>
								</div>
								<div style="text-align:right">
									<?= Html::submitButton('Enter Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>', ['class'=>'btn btn-info']) ?>
								</div>
							<?php ActiveForm::end(); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

</div>
