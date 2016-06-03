<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');

$this->registerJsFile('@exp/js/getLocation.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@exp/js/jstimezonedetect/dist/jstz.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="site-login">

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
		
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Admin Login</h3>
				</div>
				<div class="panel-body">
				
					<?php $form = ActiveForm::begin([
							'id'						=> 'login-form',
							'fieldConfig' => [
								'template' => "{beginWrapper}\n{input}\n{endWrapper}"
							],
						]); ?>
						
						<input type="hidden" name="latitude" id="latitude" value="">
						<input type="hidden" name="longitude" id="longitude" value="">
						<input type="hidden" name="timezone" id="timezone" value="">

						<?= $form->field($model, "email")
							->label('')
							->textInput(['placeholder'=>'E-mail']);
						?>
						<?= $form->field($model, "password")
							->label('')
							->passwordInput(['placeholder'=>'Password']);
						?>
						
						<div class="row">
							<div class="col-sm-6">
								<a href="<?=Url::To(['site/request-password-reset']);?>">
									<span class="glyphicon glyphicon-question-sign text-primary" style="font-size: 24px; margin-top:4px" aria-hidden="true"></span>
									<span style="vertical-align:25%;">I forgot my password</span>
								</a>
							</div>
							<div class="col-sm-6 pull-right last" style="text-align:right">
								<?= Html::submitButton('Login&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>', ['class'=>'btn btn-info']) ?>
							</div>
						</div>

					<?php ActiveForm::end(); ?>
				
				</div>
			</div>

		</div>
	</div>
  
</div>
