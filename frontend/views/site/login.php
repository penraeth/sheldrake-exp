<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;


//$this->registerJsFile('//cdn.temasys.com.sg/skylink/skylinkjs/0.6.x/skylink.complete.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/getLocation.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('/js/jstimezonedetect/dist/jstz.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
//$script = "function test() {console.log('a meesage logged in console.'); alert('Hello World');}";  
//$this->registerJs($script, View::POS_END); 

?>
<div class="site-login col-md-6 col-md-offset-3">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<p>
		Don't have a login? <a href="<?= Url::to(['signup']) ?>"><?= Yii::t('app', 'Signup here.') ?></a>
	</p>
	
    <div class="col-lg-5 well bs-component">

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        	
        	<!--<input type="hidden" name="compatibility" id="compatibility" value="">-->
        	<input type="hidden" name="latitude" id="latitude" value="">
        	<input type="hidden" name="longitude" id="longitude" value="">
        	<input type="hidden" name="timezone" id="timezone" value="">

			<?= $form->field($model, 'email') ?>        
			<?= $form->field($model, 'password')->passwordInput() ?>
	
			<div style="color:#999;margin:1em 0">
				<?= Yii::t('app', 'If you forgot your password you can') ?>
				<?= Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']) ?>.
			</div>
	
			<div class="form-group">
				<?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
			</div>

        <?php ActiveForm::end(); ?>

    </div>
  
</div>
