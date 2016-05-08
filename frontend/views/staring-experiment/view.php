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

<div class="staring-experiment-view col-md-6 col-md-offset-3">

    <div class="panel panel-default" style="padding:8px;">
		<h4>
			<b>Experiment: <?= Html::encode($experiment->name) ?></b>
		</h4>
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
	</div>
    
    <?php if ($isHost): ?>
		<div class="panel panel-default" style="padding:8px;">
			<h4><b>Invitees</b></h4>
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
	<?php endif; ?>
	
	<?php if ( !$experiment->datecompleted ): ?>
	
		<?php if ($isHost): ?>
			<div class="panel panel-default" style="padding:8px; text-align:right">
				<?php $form = ActiveForm::begin([
						'id' => 'form-participant',
						'action' => Url::To(['staring-participant/create'])
					]); ?>
					<input type="hidden" id="staringexperiment-id" name="StaringParticipant[exp_id]" value="<?=$experiment->id;?>">
					<input type="hidden" id="staringexperiment-id" name="StaringParticipant[observers]" value="0">
					<input type="hidden" id="staringexperiment-id" name="StaringParticipant[relationship]" value="0">
					<?= Html::submitButton(Yii::t('app', 'Enter Experiment &raquo;'), ['class' => 'btn btn-default btn-sm', 'name' => 'login-button']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		<?php else: ?>
			<div class="panel panel-default" style="padding:8px;">
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
								->label('Observers')
								->textInput(['placeholder'=>'']);
							?>
						</div>
						<div class="col-sm-6">
							<?= $form->field($participant, "relationship")
								->label('Relationship')
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
						<?= Html::submitButton(Yii::t('app', 'Enter Experiment &raquo;'), ['class' => 'btn btn-default btn-sm', 'name' => 'login-button']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		<?php endif; ?>
		
	<?php endif; ?>
</div>
