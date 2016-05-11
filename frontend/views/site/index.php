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
    <div class="body-content">
  
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Welcome, <?= Yii::$app->user->identity->first_name ?></h3>
					</div>
					<div class="panel-body">
					
					   <div class="row">
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-body">
										<p><a class="btn btn-primary" href="<?= Url::to(['staring-experiment/create']) ?>">CREATE Staring Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></p>
										<p class="small">You will be the subject and invite others to join your experiment as observers.</p>
										<p>
											<a href="<?=Url::to(['staring-experiment/list', 'type'=>'host', 'status'=>'active']);?>">
												<span class="glyphicon glyphicon-stop" aria-hidden="true"></span> Active Experiments
												<?php if ($badges['host_open']): ?><span class="badge pull-right"><?=$badges['host_open'];?></span><?php endif; ?>
											</a>
										</p>
										<p>
											<a href="<?=Url::to(['staring-experiment/list', 'type'=>'host', 'status'=>'completed']);?>">
												<span class="glyphicon glyphicon-stop" aria-hidden="true"></span> Completed Experiments
												<?php if ($badges['host_done']): ?><span class="badge pull-right"><?=$badges['host_done'];?></span><?php endif; ?>
											</a>
										</p>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="panel panel-default">
									<div class="panel-body">
										<p><a class="btn btn-primary" href="<?= Url::to(['staring-experiment/list-by-invite']) ?>">JOIN Staring Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></p>
										<p class="small">Become an observer in an experiment you've been invited to.</p>
										<p>
											<a href="<?=Url::to(['staring-experiment/list-by-invite', 'status'=>'active']);?>">
												<span class="glyphicon glyphicon-stop" aria-hidden="true"></span> Active Invitations
												<?php if ($badges['guest_open']): ?><span class="badge pull-right"><?=$badges['guest_open'];?></span><?php endif; ?>
											</a>
										</p>
										<p>
											<a href="<?=Url::to(['staring-experiment/list-by-participant', 'type'=>'host', 'status'=>'completed']);?>">
												<span class="glyphicon glyphicon-stop" aria-hidden="true"></span> Completed Invitations
												<?php if ($badges['guest_done']): ?><span class="badge pull-right"><?=$badges['guest_done'];?></span><?php endif; ?>
											</a>
										</p>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>

    </div>
</div>
