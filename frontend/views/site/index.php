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
  
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Welcome, <?= Yii::$app->user->identity->first_name ?></h3>
					</div>
					<div class="panel-body">
					
					   <div class="row">
							<div class="col-sm-6">
								<div class="panel panel-default last">
									<div class="panel-body">
										<p><a class="btn btn-info" href="<?= Url::to(['staring-experiment/create']) ?>">CREATE Staring Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></p>
										<p class="small">You will be the subject and invite others to join your experiment as observers.</p>
										<p>
											<a href="#" onClick="showDataTable('host_open');">
												<span class="glyphicon glyphicon-stop blue" aria-hidden="true"></span> <strong>Active Experiments</strong>
												<?php if ($data['host_open']): ?><span class="badge-index pull-right"><?=count($data['host_open']);?></span><?php endif; ?>
											</a>
										</p>
										<p>
											<a href="#" onClick="showDataTable('host_done');">
												<span class="glyphicon glyphicon-stop blue" aria-hidden="true"></span> <strong>Completed Experiments</strong>
												<?php if ($data['host_done']): ?><span class="badge-index pull-right"><?=count($data['host_done']);?></span><?php endif; ?>
											</a>
										</p>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="panel panel-default last">
									<div class="panel-body">
										<p><a class="btn btn-info" href="#" onClick="showDataTable('guest_open');">JOIN Staring Experiment&nbsp;&nbsp;<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></p>
										<p class="small">Become an observer in an experiment you've been invited to.</p>
										<p>
											<a href="#" onClick="showDataTable('guest_open');">
												<span class="glyphicon glyphicon-stop blue" aria-hidden="true"></span> <strong>Active Invitations</strong>
												<?php if ($data['guest_open']): ?><span class="badge-index pull-right"><?=count($data['guest_open']);?></span><?php endif; ?>
											</a>
										</p>
										<p>
											<a href="#" onClick="showDataTable('guest_done');">
												<span class="glyphicon glyphicon-stop blue" aria-hidden="true"></span> <strong>Completed Invitations</strong>
												<?php if ($data['guest_done']): ?><span class="badge-index pull-right"><?=count($data['guest_done']);?></span><?php endif; ?>
											</a>
										</p>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
				
				<div class="panel panel-info collapse" id="div_data_host_open">
					<div class="panel-heading">
						<h3 class="panel-title">Active Experiments</h3>
					</div>
					<div class="panel-body panel-table-data" id="panel_host_open">
						<table id="data_host_open" class="table table-condensed" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="small">Name</th>
									<th class="small">Created</th>
									<th class="small">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data['host_open'] as $experiment): ?>
								<tr>
									<td><?=$experiment['name'];?></td>
									<td><?=TzHelper::convertLocal( $experiment['created_at'] );?></td>
									<td><a class="btn btn-info small" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">View</a></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="panel panel-info collapse" id="div_data_host_done">
					<div class="panel-heading">
						<h3 class="panel-title">Completed Experiments</h3>
					</div>
					<div class="panel-body panel-table-data" id="panel_host_done">
						<table id="data_host_done" class="table table-condensed" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="small">Name</th>
									<th class="small">Completed</th>
									<th class="small">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data['host_done'] as $experiment): ?>
								<tr>
									<td><?=$experiment['name'];?></td>
									<td><?=TzHelper::convertLocal( $experiment['datecompleted'] );?></td>
									<td><a class="btn btn-info" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">View</a></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="panel panel-info collapse" id="div_data_guest_open">
					<div class="panel-heading">
						<h3 class="panel-title">Active Invitations</h3>
					</div>
					<div class="panel-body panel-table-data" id="panel_guest_open">
						<table id="data_guest_open" class="table table-condensed" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="small">Name</th>
									<th class="small">Created</th>
									<th class="small">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data['guest_open'] as $experiment): ?>
								<tr>
									<td><?=$experiment['name'];?></td>
									<td><?=TzHelper::convertLocal( $experiment['created_at'] );?></td>
									<td><a class="btn btn-info" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">View</a></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="panel panel-info collapse" id="div_data_guest_done">
					<div class="panel-heading">
						<h3 class="panel-title">Completed Invitations</h3>
					</div>
					<div class="panel-body panel-table-data" id="panel_guest_done">
						<table id="data_guest_done" class="table table-condensed" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="small">Name</th>
									<th class="small">Completed</th>
									<th class="small">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($data['guest_done'] as $experiment): ?>
								<tr>
									<td><?=$experiment['name'];?></td>
									<td><?=TzHelper::convertLocal( $experiment['datecompleted'] );?></td>
									<td><a class="btn btn-info" href="<?=Url::to(['staring-experiment/view', 'id' => $experiment['id']]);?>">View</a></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				
			</div>
		</div>
		
    </div>
</div>


