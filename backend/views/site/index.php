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
			$right = ($trial->observers == $trial->judgment)?1:0;
			
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
		return TzHelper::convertLocal($model->datecompleted);
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

    <h3>Staring Experiment Results</h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'beforeRow' => 'row_totals',
        'headerRowOptions' => ['class'=>'small'],
        'rowOptions' => ['class'=>'small'],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        'columns' => [
        	// DATE
            [
            	'label'					=> 'Date',
            	'attribute'				=> 'datecompleted',
            	'content'				=> 'col_date',
            	'contentOptions'		=> ['style'=>'white-space: nowrap'],
            ],
            
            // NAME
            [
            	'label'					=> 'Name',
            	'attribute'				=> 'name',
            	'value'					=> 'name',
            	'contentOptions'		=> ['style'=>'white-space: nowrap'],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
            // HOST
            [
            	'label'					=> 'Subject',
            	'attribute'				=> 'hostName',
            	'value'					=> function($model) { return $model->hostName; },
            	'contentOptions'		=> ['style'=>'white-space: nowrap'],
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            ],
            
          	// HOST GENDER
         	[
         		'label'					=> 'Gen',
         		'attribute'				=> 'hostGender',
         		'value'					=> function($model) { return substr(Yii::$app->params['genders'][$model->host->gender], 0, 1); },
            	'contentOptions'		=> ['style'=>'white-space: nowrap', 'align'=>'center'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'hostGender', [null=>'-',0=>'F',1=>'M'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
        	
         	// PARTICIPANTS
         	[
         		'attribute' => 'Participants',
         		'content' => 'col_participants',
            	'contentOptions'		=> ['style'=>'white-space: nowrap'],
         	],
         	
         	// RELATIONSHIPS
         	[
         		'label'					=> 'Relation',
         		'attribute'				=> 'relations',
         		'content' 				=> 'col_relationships',
            	'contentOptions'		=> ['style'=>'white-space: nowrap'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'relations', Yii::$app->params['relationshipFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
         	],
         	
         	// GENDERS
         	[
         		'label'					=> 'Gen',
         		'attribute'				=> 'genders',
         		'content'				=> 'col_genders',
            	'contentOptions'		=> ['style'=>'white-space: nowrap', 'align'=>'center'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'genders', Yii::$app->params['genderFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:center'],
         	],
         	
         	// DISTANCES
         	[
         		'label'					=> 'Distance',
         		'attribute'				=> 'distances',
         		'content'				=> 'col_distances',
            	'contentOptions'		=> ['style'=>'white-space: nowrap', 'align'=>'right'],
            	'filter'				=> Html::activeDropDownList($searchModel, 'distances', Yii::$app->params['distanceFilter'], ['class'=>'form-control input-xs input-inline']),
            	'filterInputOptions'	=> ['class'=>'form-control input-xs'],
            	'headerOptions'			=> ['style'=>'text-align:right'],
         	],
         	
         	// OBSERVERS
         	[
         		'attribute'				=> 'Obs',
         		'content'				=> 'col_observers',
            	'contentOptions'		=> ['align'=>'right'],
            	'headerOptions'			=> ['style'=>'text-align:right'],
         	],
         	
         	['attribute' => 'Total',  'value' => function($model) { return $model->result_observers; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right', 'style'=>'font-weight:bold']],
         	['attribute' => 'Trials', 'value' => function($model) { return $model->row_totals['all_count']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Right',  'value' => function($model) { return $model->row_totals['all_right']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'FB',     'value' => function($model) { return $model->row_totals['fby_count']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Right',  'value' => function($model) { return $model->row_totals['fby_right']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'No FB',  'value' => function($model) { return $model->row_totals['fbn_count']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Right',  'value' => function($model) { return $model->row_totals['fbn_right']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Seen',   'value' => function($model) { return $model->row_totals['oby_count']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Right',  'value' => function($model) { return $model->row_totals['oby_right']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Unseen', 'value' => function($model) { return $model->row_totals['obn_count']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],
         	['attribute' => 'Right',  'value' => function($model) { return $model->row_totals['obn_right']; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right']],

       ],
    ]); ?>

</div>
