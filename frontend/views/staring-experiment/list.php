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
		<h4><b><?=$title;?></b></h4>
		<table class="table table-condensed">
			<tr>
				<th>Name</th>
				<th>Created</th>
				<th>Action</th>
			</tr>
			<?php foreach ($experiments as $experiment): ?>
				<tr valign="middle">
					<td><?=$experiment['name'];?></td>
					<td><?=$experiment['created_at'];?></td>
					<td><a href="<?=Url::to(['view', 'id' => $experiment['id']]);?>">View</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
	
</div>
