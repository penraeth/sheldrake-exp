<?php
use nenad\passwordStrength\PasswordInput;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('app', 'Signup to participate in experiments');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/js/getLocation.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


?>
<div class="site-signup">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="col-lg-12 well bs-component">

        <p><?= Yii::t('app', 'This is your personal account. Upon completion, you\'ll be logged in and can create or join an experiment.') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        
        	<input type="hidden" name="latitude" id="latitude" value="">
        	<input type="hidden" name="longitude" id="longitude" value="">

            <?= $form->field($model, 'email', [
				'feedbackIcon' => [
					'default' => 'envelope'
				]
				])->textInput(['placeholder'=>'For experiment management only; will not be shared']) ?>
    
            <?= $form->field($model, 'password')->widget(PasswordInput::classname(), []) ?>
			<div class="row">
				<div class="col-xs-6">
					<?= $form->field($model, 'first_name') ?>
				</div>
				<div class="col-xs-6">
					<?= $form->field($model, 'last_name') ?>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<p>
						<?= Yii::t('app', 'We ask for age and gender in order to evaluate relative effect sizes across all experiments. No personal information will be shared.') ?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-3">
					<?= $form->field($model, 'yearofbirth')->textInput($options = ['size'=>4,'maxlength'=>4]) ?>
				</div>
				<div class="col-xs-6">
					<?= $form->field($model, 'gender')->radioList(Yii::$app->params['genders'], ['inline'=>true]) ?>
				</div>
			</div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>