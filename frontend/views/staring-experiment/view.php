<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StaringExperiment */

#$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Staring Experiments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staring-experiment-view col-md-6 col-md-offset-3">

    <div class="panel panel-default" style="padding:8px;">
		<h4>
			<b>Experiment: <?= Html::encode($experiment->name) ?></b>
			<a class="btn btn-sm btn-default pull-right" href="<?= Url::to(['']) ?>">Start</a>
		</h4>
		<table class="table table-condensed">
			<tr valign="middle">
				<td>Created</td>
				<td><?=$experiment['created_at'];?></td>
			</tr>
			<tr valign="middle">
				<td>Started</td>
				<td><?=($experiment['datestarted'])?$experiment['datestarted']:'pending';?></td>
			</tr>
			<tr valign="middle">
				<td>Completed</td>
				<td><?=($experiment['datecompleted'])?$experiment['datecompleted']:'pending';?></td>
			</tr>
		</table>
	</div>
    
    <div class="panel panel-default" style="padding:8px;">
		<h4><b>Invitees</b></h4>
		<table class="table table-condensed">
			<tr>
				<th>E-mail</th>
				<th>Status</th>
			</tr>
			<?php foreach ($invitations as $invitation): ?>
				<?php
					switch($invitation['email_status']) {
						case 1: $status = 'sent'; break;
						case -1: $status = 'failed'; break;
						default: $status = 'unknown';
					}
				?>
				<tr valign="middle">
					<td><?=$invitation['email'];?></td>
					<td><?=$status;?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	
</div>
