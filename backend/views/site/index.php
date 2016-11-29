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
	//use yii\grid\GridView;
	use kartik\grid\GridView;
	use yii\widgets\ListView;
	use common\models\Product;
	use common\helpers\TzHelper;
	
	$allTrials=0;
		
	// ROW AGGREGATE FUNCTION
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
			$col_totals['all_count']++;
			$col_totals['all_right']+= $right;
			if ($trial->feedback) {
				$model->row_totals['fby_count']++;
				$model->row_totals['fby_right']+= $right;
				$col_totals['fby_count']++;
				$col_totals['fby_right']+= $right;
			} else {
				$model->row_totals['fbn_count']++;
				$model->row_totals['fbn_right']+= $right;
				$col_totals['fbn_count']++;
				$col_totals['fbn_right']+= $right;
			}
			if ($trial->observers) {
				$model->row_totals['oby_count']++;
				$model->row_totals['oby_right']+= $right;
				$col_totals['oby_count']++;
				$col_totals['oby_right']+= $right;
			} else {
				$model->row_totals['obn_count']++;
				$model->row_totals['obn_right']+= $right;
				$col_totals['obn_count']++;
				$col_totals['obn_right']+= $right;
			}
		}
	}
	
	
	// COLUMN FUNCTIONS 
	
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

	
	function numberoftrials_count($model, $key, $index, $column) {
		$attr = $column->attribute;
		return $model->row_totals[$attr.'_count'];
	}
	
	function numberoftrials_right($model, $key, $index, $column) {
		$attr = $column->attribute;
		return $model->row_totals[$attr.'_right'];
	}
	
	
	function col_date($model, $key, $index, $column) {
		return TzHelper::convertLocal($model->datecompleted, 'm/d/y, H:i T');
	}
	
	function col_name($model, $key, $index, $column) {
		$experimentView = "<a href=" . Url::to(['staring-experiment/view', 'id' => $model->id, 'user_id' => $model->user_id]) . " target=_blank>" . $model->name . "</a>";
		return $experimentView;
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
?>
	

<div class="document-index">

    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
    
		'id' 				=> 'report-grid',
		'dataProvider'		=> $dataProvider,
		'filterModel'		=> $searchModel,
        'headerRowOptions'	=> ['class' => 'small'],
		'filterRowOptions'	=> ['class' => 'small'],
        'summaryOptions' 	=> ['class' => 'small'],
        'rowOptions' 		=> ['class' => 'small'],
        
		'panel'				=> ['type'=> 'default',
								'heading'=> 'Staring Experiment Results',
								],
		'panelTemplate' 	=> '<div class="panel"> {panelHeading} {panelBefore} {items} {panelAfter} {panelFooter} </div>',
		'panelHeadingTemplate' => '<div class="pull-right"> {summary} {export} </div> <h4 style="font-weight:bold"> {heading} </h4> <div class="clearfix"></div>',
		'panelBeforeTemplate' => '{before} <div class="clearfix"></div>',
		
		'showFooter'		=> false,
		'showPageSummary'	=> false,
		//'pageSummaryRowOptions'=> '',
		
		'pjax'				=> true,
		'bordered'			=> true,
		'striped'			=> true,
		'condensed'			=> true,
		'responsive'		=> true, //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
		'hover'				=> false,
		'persistResize'		=> false,
		
		// set export properties
		'export'=>[
			'fontAwesome'	=> true,
			'label'			=> 'Export'
		],
		'exportConfig' => [
			GridView::EXCEL => [],
			GridView::CSV 	=> [],
			GridView::TEXT	=> [],
			GridView::HTML 	=> [],
		],
		
        'beforeRow' 		=> 'row_totals',
        'formatter' 		=> ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
		'footerRowOptions'	=> ['style'=>'font-weight:bold;'],
        
		/* not used
        'summary' => '<span class="small" style="padding-top:0px;">Showing {begin}-{end} of {totalCount} records</span>',
		'pageSummary' => [
			function ($summary, $data, $widget) { return $summary; }
		],
		
		'toolbar'=> [
			['content'=>'Something'],
			'{export}',
			'{toggleData}',
		],
		
		// from previous gridview
        'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'],
        'layout' => "{items}{pager}",
        'pager' => ['options' => ['class' => 'pagination pagination-sm pull-right'] ],
		*/		
        
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
         		'content' 				=> 'col_name',
            	'value'					=> 'name',
            	'contentOptions'		=> [],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
            // Subject
            [
            	'label'					=> 'Subject',
            	'attribute'				=> 'hostName',
            	'value'					=> function($model) { return $model->hostName; },
            	'contentOptions'		=> ['class'=>'subject'],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
          	// Subject GENDER
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
         		'attribute' 			=> 'Observers',
         		'content' 				=> 'col_participants',
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
         		'label'					=> 'Distance',
         		'attribute'				=> 'distances',
         		'content'				=> 'col_distances',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'right'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'distances', Yii::$app->params['distanceFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	// OBSERVERS
         	[
         		'label'					=> '#Obs',
         		'content'				=> 'col_observers',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'center'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	// TOTAL OBSERVERS
         	[
         		'label'					=> 'Total Obs',
         		'attribute'				=> 'result_observers',
         		'value'					=> function($model) { return $model->result_observers; },
         		'contentOptions'		=> ['align'=>'right', 'style'=>'font-weight:bold'],
            	'filter'				=> Html::activeTextInput($searchModel, 'result_observers', ['class'=>'form-control input-xs', 'style'=>'width:4em;']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
         		'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	// RESULTS
         	[
         		'label'					=> 'Trials',
         		'attribute'				=> 'all',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#efe; font-weight:bold', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				//'footer'				=> col_result_total($model, $key, $index, $column),
				//'pageSummary'			=> function ($summary, $data, $widget) { return $data; },
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
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#f6efee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],
         	[
         		'label'					=> 'Unseen',
         		'attribute'				=> 'obn',
         		'content'				=> 'col_result',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#f6efee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
			],

       ],
    ]);
    
?>

</div>
