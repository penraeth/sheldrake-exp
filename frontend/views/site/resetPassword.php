<?php
use nenad\passwordStrength\PasswordInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = Yii::t('app', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
		
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
				</div>
				<div class="panel-body">
					<?php $form = ActiveForm::begin([
							'id'						=> 'reset-password-reset-form',
							'fieldConfig' => [
								'template' => "{beginWrapper}\n{input}\n{endWrapper}"
							],
						]); ?>
						<?= $form->field($model, "password")
							->label('')
							->widget(PasswordInput::classname(), [
								'options' => ['placeholder'=>'New password']
							]);
						?>
						<div class="row">
							<div class="col-sm-12 pull-right last" style="text-align:right">
								<?= Html::submitButton('Save&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>', ['class'=>'btn btn-info']) ?>
							</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
			
		</div>
    </div>

</div>
