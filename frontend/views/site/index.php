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
            <div class="col-sm-4 col-sm-offset-2">
            	<div class="panel panel-default" style="padding:12px">
					<p><a class="btn btn-default" href="<?= Url::to(['staring-experiment/create']) ?>">CREATE Staring Experiment &raquo;</a></p>
					<p>You will be the subject and invite others to join your experiment as observers.</p>
					<p>
						<a href="<?=Url::to(['staring-experiment/active']);?>">
							&raquo; Your Active Experiments
							<?php if ($badges['active']): ?><span class="badge pull-right"><?=$badges['active'];?></span><?php endif; ?>
						</a>
					</p>
					<p>&raquo; Your Completed Experiments</p>
				</div>
            </div>
            <div class="col-sm-4">
            	<div class="panel panel-default" style="padding:12px">
					<p><a class="btn btn-default" href="<?= Url::to(['staring-experiment/create']) ?>">JOIN Staring Experiment &raquo;</a></p>
					<p>Become an observer in an experiment you've been invited to.</p>
					<p>&raquo; Active Experiments You've Joined</p>
					<p>&raquo; Completed Experiments You've Joined</p>
				</div>
            </div>
        </div>

    </div>
</div>

