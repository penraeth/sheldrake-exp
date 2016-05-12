<?php
use nenad\passwordStrength\PasswordInput;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('app', 'Signup to participate in experiments');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@exp/js/getLocation.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@exp/js/jstimezonedetect/dist/jstz.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


?>
<div class="site-signup">

	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
		
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
				</div>
				<div class="panel-body">

					<p class="small"><?= Yii::t('app', 'This is your personal account. Upon completion, you\'ll be logged in and can create or join an experiment.') ?></p>

					<?php $form = ActiveForm::begin([
							'id'						=> 'signup-form',
							'formConfig' => [
								'showLabels' => false,
								'showErrors' => false
							],
						]); ?>
						<input type="hidden" name="latitude" id="latitude" value="">
						<input type="hidden" name="longitude" id="longitude" value="">
						<input type="hidden" name="timezone" id="timezone" value="">

						<?= $form->field($model, 'email', [
							'feedbackIcon' => [
								'default' => 'envelope'
							]
							])->textInput(['placeholder'=>'E-mail (for experiments only; will not be shared)']) ?>
	
						<?= $form->field($model, 'password')
							->widget(PasswordInput::classname(), [
								'options' => ['placeholder'=>'Password']
							]) ?>
						
						<div class="row">
							<div class="col-sm-6">
								<?= $form->field($model, 'first_name')
									->textInput(['placeholder'=>'First Name']); ?>
							</div>
							<div class="col-sm-6">
								<?= $form->field($model, 'last_name')
									->textInput(['placeholder'=>'Last Name']); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<p class="small">
									<?= Yii::t('app', 'We ask for age and gender in order to evaluate relative effect sizes across all experiments. No personal information will be shared.') ?>
								</p>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<?= $form->field($model, 'yearofbirth')
									->textInput(['size'=>3,'maxlength'=>3,'placeholder'=>'Age']) ?>
							</div>
							<div class="col-sm-6">
								<?= $form->field($model, 'gender')
									->radioList(Yii::$app->params['genders'], ['inline'=>true]) ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12 pull-right last" style="text-align:right">
								<?= Html::submitButton('Signup&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>', ['class'=>'btn btn-info']) ?>
							</div>
						</div>

					<?php ActiveForm::end(); ?>
					
				</div>
			</div>
			
		</div>
    </div>
    
</div>
