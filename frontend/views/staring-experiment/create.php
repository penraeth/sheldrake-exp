<?php

	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;


	/* @var $this yii\web\View */
	/* @var $model app\models\StaringExperiment */

	$this->title = 'Create Staring Experiment';
	$this->params['breadcrumbs'][] = ['label' => 'Staring Experiments', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
	$inviteCount = 0;
	
	if ($experiment['name'] == '') {
		$experiment['name'] = $identity->first_name.' '.date('m/d/y');
	}
?>


<script>
	var inviteCount = 0;
	function addInvitee() {
		inviteCount++;
		$('#invitees').append('<div class="form-group required"><input type="text" id="userInvitation-email-'+inviteCount+'" class="form-control" name="UserInvitation['+inviteCount+'][email]" placeholder="e-mail address"></div>');
		$('#userInvitation-email-'+inviteCount).focus();
	}
</script>
<style>
	.has-error input[type="text"].form-control { background-color: #fdd; }
</style>



<div class="staring-experiment-create col-sm-6 col-sm-offset-3">

    <h1>
    	<?= Html::encode($this->title) ?>
    </h1>
    <br>

	<?php $form = ActiveForm::begin([
			'id'						=> 'create-form',
			'fieldConfig' => [
				'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}"
			],
			'enableClientValidation'	=> false,
			'validateOnSubmit'			=> false,
		]); ?>
	
		<?= $form->field($experiment, "name")
			->label('Experiment name')
			->textInput(['placeholder'=>'']);
		?>
		<label class="control-label" for="staringexperiment-name">Enter invitees, one per line</label>
		<div id="invitees">
			<?php for ($i=0; $i<count($invitations); $i++): ?>
				<?php $invitation = $invitations[$i]; ?>
				<?php if ($i==0 || $invitation['email']): ?>
					<?= $form->field($invitation, "[$inviteCount]email", ['template'=>'{input}'])
						->label('')
						->textInput(['placeholder'=>'e-mail address']);
					?>
					<script>inviteCount = <?=$inviteCount?>;</script>
					<?php $inviteCount++; ?>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
		
		<div class="row">
			<div class="col-sm-6">
				<a href="javascript:addInvitee();">
					<span class="glyphicon glyphicon-plus-sign" style="font-size: 24px; margin-top:4px; color:#080" aria-hidden="true"></span>
					<span style="vertical-align:25%;">Add invitee</span>
				</a>
			</div>
			<div class="col-sm-6" style="text-align:right">
				<?= Html::submitButton('Create &raquo;', ['class'=>'btn btn-default']) ?>
			</div>
		</div>
	<?php ActiveForm::end(); ?>

</div>
