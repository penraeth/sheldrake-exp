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
</style>

<div class="staring-experiment-view">

		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Experiment: <?= Html::encode($experiment->name) ?></h3>
					</div>
					<div class="panel-body panel-table-data">
						<table class="table table-condensed">
							<tr valign="middle">
								<td>Host</td>
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
										'id' => $experiment->id,
										'action' => Url::To(['staring-experiment/experiment'])
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
						<h3 class="panel-title">Results</h3>
					</div>
					<div class="panel-body panel-table-data">
						<table class="table table-condensed">
							<tr>
								<td align="right"><b>Trial</b></th>
								<td align="left"><b>Time</b></th>
								<td align="right"><b>Observers</b></th>
								<td align="right"><b>Judgment</b></th>
							</tr>
							<?php foreach ($trials as $trial): ?>
								<tr valign="middle">
									<td align="right"><?=$trial->trial;?></td>
									<td align="left"><?=$trial->created_at;?></td>
									<td align="right">
										<?php
											switch($trial->observers) {
												case 0: $status = 'none'; break;
												default: $status = $trial->observers;
											}
										?>
									</td>
									<td align="right">
										<?php
											switch($trial->judgment) {
												case 0: $status = 'no'; break;
												case 1: $status = 'yes'; break;
												default: $status = 'pass';
											}
										?>
									</td>
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
