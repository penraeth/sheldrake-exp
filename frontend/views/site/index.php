<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\helpers\TzHelper;
$this->title = Yii::t('app', Yii::$app->name);

$this->registerCssFile('//cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css', ['depends' => [frontend\assets\AppAsset::className()]]);

$this->registerJsFile('//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('//cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('@exp/js/data-table.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<script>
	dataTables = ['host_open','host_done','guest_open','guest_done'];
	function showDataTable(name) {
		for (dt in dataTables) {
			$('#div_data_'+dataTables[dt]).collapse('hide');
		}
		$('#div_data_'+name).collapse('show');
	}
</script>

<div class="site-index">
    <div class="body-content">
  
		<h1 style="font-size:3em; font-weight:100; color:#ccc9cc;">Welcome <?= Yii::$app->user->identity->first_name ?></h1>
		
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h3 style="color:#800">
						<b>Be the Subject</b>
					</h3>
					<p>
						Create an experiment and invite others to stare at you.
					</p>
					
					<p>
						<a class="btn btn-info" href="<?= Url::to(['staring-experiment/create']) ?>">Create New Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>
					</p>
					
					<BR>
					<h4 class="green">
						<span class="badge-index"><?=count($data['host_open']);?></span>
						Active
					</h3>
					<?php foreach ($data['host_open'] as $experiment): ?>
						<p>
							<a class="btn btn-info small" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">Open</a> &nbsp;
							<b><?=$experiment['name'];?></b>
							<span class="pale"><?=TzHelper::convertLocal( $experiment['created_at'] );?></span>
						</p>
					<?php endforeach; ?>
					
					<BR>
					<h4 class="green">
						<span class="badge-index"><?=count($data['host_done']);?></span>
						Completed
					</h3>
					<?php foreach ($data['host_done'] as $experiment): ?>
						<p>
							<a class="btn btn-info small" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">Results</a> &nbsp;
							<b><?=$experiment['name'];?></b>
							<span class="pale"><?=TzHelper::convertLocal( $experiment['created_at'] );?></span>
						</p>
					<?php endforeach; ?>
					
				</div>
				<div class="col-sm-6">
				
					<h3 style="color:#800">
						<b>Be an Observer</b>
					</h3>
					<p>
						Join experiments you've been invited to.
					</p>
					<p>
						<a class="btn btn-info" href="/exp">Refresh to See New Invitations</a>
					</p>
					
					<BR>
					<h4 class="green">
						<span class="badge-index"><?=count($data['guest_open']);?></span>
						Active
					</h3>
					<?php foreach ($data['guest_open'] as $experiment): ?>
						<p>
							<a class="btn btn-info small" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">Open</a> &nbsp;
							<b><?=$experiment['name'];?></b>
							<span class="pale"><?=TzHelper::convertLocal( $experiment['created_at'] );?></span>
						</p>
					<?php endforeach; ?>
				
					<BR>
					<h4 class="green">
						<span class="badge-index"><?=count($data['guest_done']);?></span>
						Completed
					</h3>
					<?php foreach ($data['guest_done'] as $experiment): ?>
						<p>
							<a class="btn btn-info small" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">Results</a> &nbsp;
							<b><?=$experiment['name'];?></b>
							<span class="pale"><?=TzHelper::convertLocal( $experiment['created_at'] );?></span>
						</p>
					<?php endforeach; ?>
					
				</div>
			</div>
		</div>
		
	</div>
</div>


<!--
				<div class="panel panel-info" id="div_data_host_open">
					<div class="panel-heading">
						<h3 class="panel-title">All Active Experiments</h3>
					</div>
					<div class="panel-body panel-table-data" id="panel_host_open">
						<b>You as Subject</b>
						<table id="data_host_open" class="table table-condensed" cellspacing="0" width="100%">
							<tbody>
							<?php foreach ($data['host_open'] as $experiment): ?>
								<tr>
									<td><a class="btn btn-info small" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">Start</a></td>
									<td><?=$experiment['name'];?></td>
									<td><span class="pale"><?=TzHelper::convertLocal( $experiment['created_at'] );?></span></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>


