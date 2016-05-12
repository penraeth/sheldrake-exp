<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = Yii::t('app', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
		
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
				</div>
				<div class="panel-body">
					<p class="small"><?= Yii::t('app', 'Please enter your e-mail address below. If it exists in our system, you will be sent a link to reset your password.') ?></p>

					<?php $form = ActiveForm::begin([
							'id'						=> 'request-password-reset-form',
							'fieldConfig' => [
								'template' => "{beginWrapper}\n{input}\n{endWrapper}"
							],
						]); ?>
						<?= $form->field($model, "email")
							->label('')
							->textInput(['placeholder'=>'E-mail']);
						?>
						<div class="row">
							<div class="col-sm-12 pull-right last" style="text-align:right">
								<?= Html::submitButton('Send&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>', ['class'=>'btn btn-info']) ?>
							</div>
						</div>

					<?php ActiveForm::end(); ?>
				</div>
			</div>
			
		</div>
    </div>

</div>
