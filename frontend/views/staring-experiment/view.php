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
	.pass {
		color:grey;
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
			<?php 
				$right=0;
				$wrong=0;
				$pass=0;
				$subject = ($isHost) ? "you" : $host->first_name;
				$subjectIs = ($isHost) ? "are" : "is";
				$observer = ($isHost) ? "the observers" : "you";
				
				foreach ($trials as $trial) {
	
					$observed = ($trial->observers == 0) ? 0 : 1;
					if ($trial->judgment == $observed) {
						$right++;
					} elseif ($trial->judgment > 1) {
						$pass++;
					} else {
						$wrong++;
					}
				}
					
				$totalTrials = $right + $wrong;
				$accuracy = round($right / $totalTrials * 100);
				
				if ($pass > 0) {
					print ucfirst($subject)." passed on $pass trials, leaving $totalTrials in play.";
				}
				print " Of $totalTrials trials $subject guessed correctly <b>$right</b> times and incorrectly <b>$wrong</b> times, giving an accuracy rating of <b>$accuracy%</b>. ";
				
				if ($accuracy >= 90){ 
					print "This is <i>scary</i> high; astonishingly above chance. Congratulations, $subject $subjectIs a <b>Savant</b> level detector!"; }
				elseif ($accuracy >= 75){
					print "This is <i>incredibly</i> high; significantly above chance. Congratulations, $subject $subjectIs an <b>Owl</b> level detector!"; }
				elseif ($accuracy >= 55){
					print "This is <i>very</i> high; well above chance. Congratulations, $subject $subjectIs a <b>Deer</b> level detector!"; }
				elseif ($accuracy > 50){
					print "This is slightly above chance and could indicate something more than guesswork was involved. Keep trying!"; }
				
				elseif ($accuracy == 50){
					print "This is right at the chance level, but don't be discouraged. It may take several tries to see an effect."; }
					
				elseif ($accuracy <= 10 && $isHost){
					print "This is <i>scary</i> low; astonishingly below chance. The observers are <b>Shadow</b> level hunters!"; }
				elseif ($accuracy <= 10 && !$isHost){
					print "This is <i>scary</i> low; astonishingly below chance. You are a <b>Shadow</b> level hunter!"; }
				elseif ($accuracy <= 25 && $isHost){
					print "This is <i>incredibly</i> low; significantly below chance. The observers are <b>Ninja</b> level hunters!"; }
				elseif ($accuracy <= 25 && !$isHost){
					print "This is <i>incredibly</i> low; significantly below chance. You are a <b>Ninja</b> level hunter!"; }
				elseif ($accuracy <= 45 && $isHost){
					print "This is <i>very</i> low; well below chance. The observers are <b>Tiger</b> level hunters!"; }
				elseif ($accuracy <= 45 && !$isHost){
					print "This is <i>very</i> low; well below chance. You are a <b>Tiger</b> level hunter!"; }
				elseif ($accuracy < 50){
					print "This is slightly below chance and could indicate something more than guesswork was involved. Keep trying!"; }
				
			?>
			</p>
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
								<td><?=$host->first_name;?></td>
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
	
	
	<?php if ( $experiment->datecompleted ): ?>
		<!-- completed -->
		
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
									} elseif ($trial->judgment > 1) {
										$class ='pass';
										$judgment = 'pass';
									} else {
										$class ='wrong';
										$judgment = 'wrong';
									}
								?>
								<tr valign="middle">
									<td align="right" title="<?=$trial->created_at;?>"><?=$trial->trial;?></td>
									<td align="center"><?=$observers;?></td>
									<td align="center" class="<?=$class;?>"><?=$judgment;?></td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			
			</div>
		</div>
	
	<?php else: ?>
		<!-- active -->
		
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<?php if (!$isHost): ?>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Please provide your observer information</h3>
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
											->label('Number of observers')
											->textInput(['placeholder'=>'']);
										?>
									</div>
									<div class="col-sm-6">
										<?= $form->field($participant, "relationship")
											->label('Relationship to host')
											->dropDownList([
												'' => 'Select...',
												'1'	=> 'Close friend',
												'2'	=> 'Acquaintance',
												'3' => 'Not known'
											]);
										?>
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
