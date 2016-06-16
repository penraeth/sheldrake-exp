<style tyle="text/css">

	td {
		white-space: nowrap;
	}
	.subject {
		color:#009900;
	}
	
	.observer {
		color:#000099;
	}
</style>

<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\ListView;
	use common\models\Product;
	use common\helpers\TzHelper;
		
	
	// ROW AGGREGATE FUNCTIONS
	
	function row_totals($model, $key, $index, $column) {
		$model->row_totals['all_count'] = 0;
		$model->row_totals['all_right'] = 0;
		$model->row_totals['fby_count'] = 0;
		$model->row_totals['fby_right'] = 0;
		$model->row_totals['fbn_count'] = 0;
		$model->row_totals['fbn_right'] = 0;
		$model->row_totals['oby_count'] = 0;
		$model->row_totals['oby_right'] = 0;
		$model->row_totals['obn_count'] = 0;
		$model->row_totals['obn_right'] = 0;
		
		
		foreach ($model->staringTrials as $trial) {
			$right = 0;
			if ($trial->observers > 0   &&  $trial->judgment > 0) {
				$right = 1;
			}
			if ($trial->observers == 0   &&  $trial->judgment == 0) {
				$right = 1;
			}
			
			$model->row_totals['all_count']++;
			$model->row_totals['all_right']+= $right;
			if ($trial->feedback) {
				$model->row_totals['fby_count']++;
				$model->row_totals['fby_right']+= $right;
			} else {
				$model->row_totals['fbn_count']++;
				$model->row_totals['fbn_right']+= $right;
			}
			if ($trial->observers) {
				$model->row_totals['oby_count']++;
				$model->row_totals['oby_right']+= $right;
			} else {
				$model->row_totals['obn_count']++;
				$model->row_totals['obn_right']+= $right;
			}
		}
	}
	
	
	// COLUMN FUNCTIONS 
	
	function col_date($model, $key, $index, $column) {
		return TzHelper::convertLocal($model->datecompleted, 'm/d/y, H:i T');
	}

	function col_participants($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= $participant->user->first_name.' '.$participant->user->last_name;
			$data .= '<br>';
		}
		return $data;
	}

	function col_relationships($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= Yii::$app->params['relationships'][$participant->relationship];
			$data .= '<br>';
		}
		return $data;
	}

	function col_genders($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= substr(Yii::$app->params['genders'][$participant->user->gender], 0, 1);
			$data .= '<br>';
		}
		return $data;
	}

	function col_observers($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= $participant->observers;
			$data .= '<br>';
		}
		return $data;
	}

	function col_distances($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			if ($participant->distance >= 0) {
				$data .= number_format($participant->distance / 5280, 1) .' mi';
			} else {
				$data .= '-';
			}
			$data .= '<br>';
		}
		return $data;
	}
	
	function col_result($model, $key, $index, $column) {
		$data = '';
		$attr = $column->attribute;
		$chance = round($model->row_totals[$attr.'_count'] / 2, 1);
		
		if ($model->row_totals[$attr.'_right'] == 0) {
			$data .= '<span class="glyphicon glyphicon-minus" style="padding-right:4px; color:#cc0000" aria-hidden="true"></span>';
		} else if ($model->row_totals[$attr.'_right'] == $model->row_totals[$attr.'_count']) {
			$data .= '<span class="glyphicon glyphicon-plus" style="padding-right:4px; color:#0088aa" aria-hidden="true"></span>';
		} else if ($model->row_totals[$attr.'_right'] > $chance) {
			$data .= '<span class="glyphicon glyphicon-plus" style="padding-right:4px; color:#00aa00" aria-hidden="true"></span>';
		} else if ($model->row_totals[$attr.'_right'] < $chance) {
			$data .= '<span class="glyphicon glyphicon-minus" style="padding-right:4px; color:#cc0000" aria-hidden="true"></span>';
		} else {
			$data .= '<span class="glyphicon glyphicon-remove" style="padding-right:4px; color:#aaa" aria-hidden="true"></span>';
		}
		$data .= $model->row_totals[$attr.'_right'].'/'.$model->row_totals[$attr.'_count'];
		return $data;
	}

?>

<div class="document-index">

    <h4 style="font-weight:bold">Staring Experiment Results</h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'beforeRow' => 'row_totals',
        'summaryOptions' => ['class' => 'small'],
        'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'],
        'headerRowOptions' => ['class'=>'small'],
        'rowOptions' => ['class'=>'small'],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        'layout' => "{items}{pager}",
        'pager' => ['options' => ['class' => 'pagination pagination-sm pull-right'] ],
        'summary' => '<span class="small" style="padding-top:0px;">Showing {begin}-{end} of {totalCount} records</span>',
        
        'columns' => [
        	// DATE
            [
            	'label'					=> 'Date',
            	'attribute'				=> 'datecompleted',
            	'content'				=> 'col_date',
            	'contentOptions'		=> [],
            ],
            
            // NAME
            [
            	'label'					=> 'Experiment',
            	'attribute'				=> 'name',
            	'value'					=> 'name',
            	'contentOptions'		=> [],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
            // HOST
            [
            	'label'					=> 'Subject',
            	'attribute'				=> 'hostName',
            	'value'					=> function($model) { return $model->hostName; },
            	'contentOptions'		=> ['class'=>'subject'],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
          	// HOST GENDER
         	[
         		'label'					=> 'Gender',
         		'attribute'				=> 'hostGender',
         		'value'					=> function($model) { return substr(Yii::$app->params['genders'][$model->host->gender], 0, 1); },
            	'contentOptions'		=> ['class'=>'subject', 'align'=>'center'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'hostGender', [null=>'-',0=>'F',1=>'M'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
        	
         	// PARTICIPANTS
         	[
         		'attribute' => 'Observers',
         		'content' => 'col_participants',
            	'contentOptions'		=> ['class'=>'observer'],
         	],
         	
         	// RELATIONSHIPS
         	[
         		'label'					=> 'Relation',
         		'attribute'				=> 'relations',
         		'content' 				=> 'col_relationships',
            	'contentOptions'		=> ['class'=>'observer'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'relations', Yii::$app->params['relationshipFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
         	],
         	
         	// GENDERS
         	[
         		'label'					=> 'Gender',
         		'attribute'				=> 'genders',
         		'content'				=> 'col_genders',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'center'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'genders', Yii::$app->params['genderFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	// DISTANCES
         	[
         		'label'					=> 'Distance (M)',
         		'attribute'				=> 'distances',
         		'content'				=> 'col_distances',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'right'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'distances', Yii::$app->params['distanceFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:right'],
         	],
         	
         	// OBSERVERS
         	[
         		'label'					=> '#Obs',
         		'content'				=> 'col_observers',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'center'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	// TOTAL
         	[
         		'label'					=> 'Total Obs',
         		'attribute'				=> 'result_observers',
         		'value'					=> function($model) { return $model->result_observers; },
         		'contentOptions'		=> ['align'=>'right', 'style'=>'font-weight:bold'],
            	'filter'				=> Html::activeTextInput($searchModel, 'result_observers', ['class'=>'form-control input-xs', 'style'=>'width:4em;']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
         		'headerOptions'			=> ['style'=>'text-align:center']
         	],
         	
         	// RESULTS
         	[
         		'label'					=> 'Trials',
         		'attribute'				=> 'all',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffeeee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],
         	[
         		'label'					=> 'FB',
         		'attribute'				=> 'fby',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],
         	[
         		'label'					=> 'No FB',
         		'attribute'				=> 'fbn',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],
         	[
         		'label'					=> 'Seen',
         		'attribute'				=> 'oby',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#eeffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],
         	[
         		'label'					=> 'Unseen',
         		'attribute'				=> 'obn',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#eeffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],

       ],
    ]); ?>

</div>
