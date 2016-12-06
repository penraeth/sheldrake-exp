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
	
	$all_count_t=0;
	$all_right_t=0;
	$fby_count_t=0;
	$fby_right_t=0;
	$fbn_count_t=0;
	$fbn_right_t=0;
	$oby_count_t=0;
	$oby_right_t=0;
	$obn_count_t=0;
	$obn_right_t=0;


	
	if (!empty($dataProvider->getModels())) {
        foreach ($dataProvider->getModels() as $key => $val) {
            $all_count_t += $val->all_count;
            $all_right_t += $val->all_right;
			$fby_count_t += $val->fby_count;
			$fby_right_t += $val->fby_right;
			$fbn_count_t += $val->fbn_count;
			$fbn_right_t += $val->fbn_right;
			$oby_count_t += $val->oby_count;
			$oby_right_t += $val->oby_right;
			$obn_count_t += $val->obn_count;
			$obn_right_t += $val->obn_right;
        }
    }
		
	
	// COLUMN FUNCTIONS 
	
	function sandwitch($right,$count) {
		$data = '';		
		if ($right == 0) {
			$data .= '<span class="glyphicon glyphicon-minus" style="padding-right:4px; color:#cc0000" aria-hidden="true"></span>';
		} else if ($right == $count) {
			$data .= '<span class="glyphicon glyphicon-plus" style="padding-right:4px; color:#0088aa" aria-hidden="true"></span>';
		} else if ($right > round($count / 2, 1)) {
			$data .= '<span class="glyphicon glyphicon-plus" style="padding-right:4px; color:#00aa00" aria-hidden="true"></span>';
		} else if ($right < round($count / 2, 1)) {
			$data .= '<span class="glyphicon glyphicon-minus" style="padding-right:4px; color:#cc0000" aria-hidden="true"></span>';
		} else {
			$data .= '<span class="glyphicon glyphicon-remove" style="padding-right:4px; color:#aaa" aria-hidden="true"></span>';
		}
		$data .= $right.'/'.$count;
		return $data;
	}
	
	function col_date($model, $key, $index, $column) {
		return TzHelper::convertLocal($model->datecompleted, 'm/d/y');
		//return TzHelper::convertLocal($model->datecompleted, 'm/d/y, H:i T');
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
    
    <?= GridView::widget([
    
		'id' 				=> 'report-grid',
		'dataProvider'		=> $dataProvider,
		'filterModel'		=> $searchModel,
        'headerRowOptions'	=> ['class' => ''],
		'filterRowOptions'	=> ['class' => 'tight'],
        'summaryOptions' 	=> ['class' => 'small'],
        'rowOptions' 		=> ['class' => 'small'],
        
		'panel'				=> ['type'=> 'default',
								'heading'=> 'Staring Experiment Results',
								],
		'panelTemplate' 	=> '<div class="panel"> {panelHeading} {panelBefore} {items} {panelAfter} {panelFooter} </div>',
		'panelHeadingTemplate' => '<div class="pull-right"> {summary} {export} </div> <h4 style="font-weight:bold"> {heading} </h4> <div class="clearfix"></div>',
		'panelBeforeTemplate' => '{before} <div class="clearfix"></div>',
		
		'showFooter'		=> true,
		//'showPageSummary'	=> true,
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
        'beforeRow' 		=> 'row_totals',
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
            	'attribute'				=> 'subject_name',
            	//'value'					=> function($model) { return $model->subject_Name; },
            	'contentOptions'		=> ['class'=>'subject'],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
          	// Subject GENDER
         	[
         		'label'					=> 'Sex',
         		'attribute'				=> 'subject_gender',
         		'value'					=> function($model) { return substr(Yii::$app->params['genders'][$model->subject_gender], 0, 1); },
            	'contentOptions'		=> ['class'=>'subject', 'align'=>'center'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'subject_gender', [null=>'-',0=>'F',1=>'M'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center','width' => '1.5em'],
         	],
            
          	// Subject AGE
         	[
         		'label'					=> 'Age',
         		'attribute'				=> 'subject_age',
            	'contentOptions'		=> ['class'=>'subject', 'align'=>'center'],
            	//'filter'				=> Html::activeDropDownList($searchModel, 'subject_age', [null=>'-',0=>'F',1=>'M'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center','width' => '1.5em'],
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
         		'label'					=> 'Sex',
         		'attribute'				=> 'genders',
         		'content'				=> 'col_genders',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'center'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'genders', Yii::$app->params['genderFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center','width' => '1.5em'],
         	],
         	
         	// DISTANCES
         	[
         		'label'					=> 'Miles',
         		'attribute'				=> 'distances',
         		'content'				=> 'col_distances',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'right'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'distances', Yii::$app->params['distanceFilter'], ['class'=>'form-control input-xs input-inline','style'=>'text-align:center']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	/*
         	// OBSERVERS
         	[
         		'label'					=> '#Obs',
         		'content'				=> 'col_observers',
            	'contentOptions'		=> ['class'=>'observer', 'align'=>'center'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	*/
         	
         	// TOTAL OBSERVERS
         	[
         		'label'					=> '#Obs',
         		'attribute'				=> 'result_observers',
         		'value'					=> function($model) { return $model->result_observers; },
         		'contentOptions'		=> ['align'=>'right', 'style'=>'font-weight:bold'],
            	'filter'				=> Html::activeTextInput($searchModel, 'result_observers', ['class'=>'form-control input-xs', 'style'=>'width:4em;']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
         		'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	
         	// RESULTS
         	
         	// All Trials ------------------------------------------------------------------------------
			[
         		'label'					=> 'Trials',
         		'attribute'				=> 'all',
				'format'				=> 'html',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#efe; font-weight:bold', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->all_right , $model->all_count );
											},
         		'footer'				=> sandwitch(  $all_right_t , $all_count_t ),
			],
			
         	// With Feedback ------------------------------------------------------------------------------
			[
         		'label'					=> 'FB',
         		'attribute'				=> 'fby',
				'format'				=> 'html',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->fby_right , $model->fby_count );
											},
         		'footer'				=> sandwitch(  $fby_right_t , $fby_count_t ),
			],
			
         	// Without Feedback ------------------------------------------------------------------------------
			[
         		'label'					=> 'No FB',
         		'attribute'				=> 'fbn',
				'format'				=> 'html',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->fbn_right , $model->fbn_count );
											},
         		'footer'				=> sandwitch(  $fbn_right_t , $fbn_count_t ),
			],
         	
			
         	// Seen ------------------------------------------------------------------------------
			[
         		'label'					=> 'Seen',
         		'attribute'				=> 'oby',
				'format'				=> 'html',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#f6efee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->oby_right , $model->oby_count );
											},
         		'footer'				=> sandwitch(  $oby_right_t , $oby_count_t ),
			],
			
         	// Unseen ------------------------------------------------------------------------------
			[
         		'label'					=> 'Unseen',
         		'attribute'				=> 'obn',
				'format'				=> 'html',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#f6efee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->obn_right , $model->obn_count );
											},
         		'footer'				=> sandwitch(  $obn_right_t , $obn_count_t ),
			],
         	
         	/* may need additional hidden columns for export
         	[
         		'attribute'				=> 'all_count',
            	'hidden'				=> true,
			],
         	[
         		'attribute'				=> 'all_right',
            	'hidden'				=> true,
			],
			[
         		'label'					=> 'Trials',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#efe; font-weight:bold', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'class' 				=> '\kartik\grid\FormulaColumn',
				'value' 				=> function ($model, $key, $index, $widget) {
												$p = compact('model', 'key', 'index');
												return getGlyphicon( $widget->col(11, $p) , $widget->col(10, $p) ) . $widget->col(11, $p) . '/' . $widget->col(10, $p);
											},
         		'autoFooter'			=> false,
         		'footer'				=> $all_right_t . '/' . $all_count_t,
			],
			*/

       ],
    ]);
    
?>

</div>
