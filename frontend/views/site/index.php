<?php

use yii\helpers\Url;
//use rodzadra\geolocation;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\models\User;
//https://temasys.com.sg/plugin#free-plugin
/* @var $this yii\web\View */
$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Welcome <?= Yii::$app->user->identity->first_name ?></h2>
    </div>


	
	
    <div class="body-content">

        <div class="row">
            <div class="col-sm-4">
				<p><a class="btn btn-default" href="<?= Url::to(['staring-experiment/create']) ?>">CREATE Staring Experiment &raquo;</a></p>
				<p>
					You will be the subject and invite others to join your experiment as observers.
				</p>
                <h4>Your Active Experiments</h4>
                <h4>Your Completed Experiments</h4>
            </div>
            <div class="col-sm-4">
				<p><a class="btn btn-default" href="<?= Url::to(['staring-experiment/create']) ?>">JOIN Staring Experiment &raquo;</a></p>
                <p>
                	Become an observer in an experiment you've been invited to.
                </p>

                <h4>Active Experiments You've Joined</h4>
                <h4>Completed Experiments You've Joined</h4>
            </div>
            <!--
            <?php //if (getResults) { ?>
				<div class="col-sm-4">
					<h4>Your Results</h4>
	
					<p>
						<?php 
							//print $location['latitude'];						
							var_dump($_SESSION);
						?>
					</p>
				</div>
			<?php //} ?>
			-->
        </div>

    </div>
</div>

