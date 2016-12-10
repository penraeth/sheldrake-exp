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
	$all_plus_t=0;
	$all_minus_t=0;
	$all_even_t=0;
	$fby_count_t=0;
	$fby_right_t=0;
	$fby_plus_t=0;
	$fby_minus_t=0;
	$fby_even_t=0;
	$fbn_count_t=0;
	$fbn_right_t=0;
	$fbn_plus_t=0;
	$fbn_minus_t=0;
	$fbn_even_t=0;
	$oby_count_t=0;
	$oby_right_t=0;
	$oby_plus_t=0;
	$oby_minus_t=0;
	$oby_even_t=0;
	$obn_count_t=0;
	$obn_right_t=0;
	$obn_plus_t=0;
	$obn_minus_t=0;
	$obn_even_t=0;
	
	function plusminuseven($attr,$right,$count) {
		if ($right > round($count / 2, 1)) {
			${$attr.'_plus_t'}++; 
		} else if ($right < round($count / 2, 1)) {
			${$attr.'_minus_t'}++; 
		} else {
			${$attr.'_even_t'}++; 
		}
	}
	
	foreach ($dataProvider->getModels() as $key => $val) {
		$all_count_t += $val->all_count;
		$all_right_t += $val->all_right;
			   if ($val->all_right > round($val->all_count / 2, 1)) {	$all_plus_t++; 
		} else if ($val->all_right < round($val->all_count / 2, 1)) {	$all_minus_t++; 
		} else {														$all_even_t++; }
		$fby_count_t += $val->fby_count;
		$fby_right_t += $val->fby_right;
			   if ($val->fby_right > round($val->fby_count / 2, 1)) {	$fby_plus_t++; 
		} else if ($val->fby_right < round($val->fby_count / 2, 1)) {	$fby_minus_t++; 
		} else {														$fby_even_t++; }
		$fbn_count_t += $val->fbn_count;
		$fbn_right_t += $val->fbn_right;
			   if ($val->fbn_right > round($val->fbn_count / 2, 1)) {	$fbn_plus_t++; 
		} else if ($val->fbn_right < round($val->fbn_count / 2, 1)) {	$fbn_minus_t++; 
		} else {														$fbn_even_t++; }
		$oby_count_t += $val->oby_count;
		$oby_right_t += $val->oby_right;
			   if ($val->oby_right > round($val->oby_count / 2, 1)) {	$oby_plus_t++; 
		} else if ($val->oby_right < round($val->oby_count / 2, 1)) {	$oby_minus_t++; 
		} else {														$oby_even_t++; }
		$obn_count_t += $val->obn_count;
		$obn_right_t += $val->obn_right;
			   if ($val->obn_right > round($val->obn_count / 2, 1)) {	$obn_plus_t++; 
		} else if ($val->obn_right < round($val->obn_count / 2, 1)) {	$obn_minus_t++; 
		} else {														$obn_even_t++; }
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
		
		if ($count > 0) {
			$accuracyRate = round($right/$count*100);
		} else {
			$accuracyRate = 0;
		}
		$data .= '<span style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="' . $accuracyRate . '%">' . $right .' / '.$count . '</span>';
		
		return $data;
	}
	
	function bigmac($right,$count,$plus,$minus,$even) {
		$data = sandwitch($right,$count);
		$data .= '<BR>';
		$data .= $plus . ' <span class="glyphicon glyphicon-plus" style="padding-right:4px; color:#00aa00" aria-hidden="true"></span><BR>';
		$data .= $minus . ' <span class="glyphicon glyphicon-minus" style="padding-right:4px; color:#cc0000" aria-hidden="true"></span><BR>';
		$data .= $even . ' <span class="glyphicon glyphicon-remove" style="padding-right:4px; color:#aaa" aria-hidden="true"></span>';
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
		
		'showFooter'		=> true,
		'footerRowOptions'	=> ['style'=>'font-weight:bold; padding:3px 2px; border:none'],
		//'showPageSummary'	=> true,
		//'pageSummaryRowOptions'=> '',
		
        'formatter' 		=> ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        
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
            	'contentOptions'		=> ['class'=>'observer', 'style'=>'text-align:center'],
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
         		'contentOptions'		=> ['style'=>'font-weight:bold;text-align:center'],
            	'filter'				=> Html::activeTextInput($searchModel, 'result_observers', ['class'=>'form-control input-xs', 'style'=>'width:4em;']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
         		'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	
         	// RESULTS
         	
         	// All Trials ------------------------------------------------------------------------------
			[
         		'label'					=> 'Trials',
         		'attribute'				=> 'all',
				'format'				=> 'raw',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#efe; font-weight:bold', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->all_right , $model->all_count );
											},
         		'footer'				=> bigmac(  $all_right_t , $all_count_t , $all_plus_t , $all_minus_t , $all_even_t),
				'footerOptions'			=> ['style'=>'text-align:center'],
			],
			
         	// With Feedback ------------------------------------------------------------------------------
			[
         		'label'					=> 'FB',
         		'attribute'				=> 'fby',
				'format'				=> 'raw',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->fby_right , $model->fby_count );
											},
         		'footer'				=> bigmac(  $fby_right_t , $fby_count_t , $fby_plus_t , $fby_minus_t , $fby_even_t),
				'footerOptions'			=> ['style'=>'text-align:center'],
			],
			
         	// Without Feedback ------------------------------------------------------------------------------
			[
         		'label'					=> 'No FB',
         		'attribute'				=> 'fbn',
				'format'				=> 'raw',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#ffffee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->fbn_right , $model->fbn_count );
											},
         		'footer'				=> bigmac(  $fbn_right_t , $fbn_count_t , $fbn_plus_t , $fbn_minus_t , $fbn_even_t),
				'footerOptions'			=> ['style'=>'text-align:center'],
			],
         	
			
         	// Seen ------------------------------------------------------------------------------
			[
         		'label'					=> 'Seen',
         		'attribute'				=> 'oby',
				'format'				=> 'raw',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#f6efee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->oby_right , $model->oby_count );
											},
         		'footer'				=> bigmac(  $oby_right_t , $oby_count_t , $oby_plus_t , $oby_minus_t , $oby_even_t),
				'footerOptions'			=> ['style'=>'text-align:center'],
			],
			
         	// Unseen ------------------------------------------------------------------------------
			[
         		'label'					=> 'Unseen',
         		'attribute'				=> 'obn',
				'format'				=> 'raw',
            	'contentOptions'		=> ['style'=>'white-space: nowrap; background-color:#f6efee', 'align'=>'center'],
				'headerOptions'			=> ['style'=>'text-align:center'],
				'value' 				=> function ($model, $key, $index, $widget) {
												return sandwitch( $model->obn_right , $model->obn_count );
											},
         		'footer'				=> bigmac(  $obn_right_t , $obn_count_t , $obn_plus_t , $obn_minus_t , $obn_even_t),
				'footerOptions'			=> ['style'=>'text-align:center'],
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
