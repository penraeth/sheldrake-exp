<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\widgets\ListView;
	use common\models\Product;
	use common\helpers\TzHelper;

	$this->title = 'Customer Documents';
	
	function col_dateStarted($model, $key, $index, $column) {
		return TzHelper::convertLocal($model->created_at);
	}

	function col_participants($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= $participant->user->first_name.' '.$participant->user->last_name;
			$data .= '<br>';
		}
		return $data;
	}

	function col_relationship($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= 'Very Well';
			$data .= '<br>';
		}
		return $data;
	}

	function col_gender($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			$data .= 'X';
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

	function col_distance($model, $key, $index, $column) {
		$data = '';
		foreach ($model->staringParticipants as $participant) {
			if ($participant->distance >= 0) {
				$data .= number_format($participant->distance / 5280, 1) .' mi';
			} else {
				$data = 'n/a';
			}
			$data .= '<br>';
		}
		return $data;
	}
?>

<div class="document-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' => 'datestarted', 'label' => 'Date', 'content' => 'col_dateStarted'],
            ['attribute' => 'name', 'value' => 'name', 'filterInputOptions' => ['class'=>'form-control input-xs']],
            ['attribute' => 'subject', 'value' => function($model) { return $model->subject->user->first_name.' '.$model->subject->user->last_name; }, 'filterInputOptions' => ['class'=>'form-control input-xs']],
         	['attribute' => 'G', 'value' => 'subject.user.gender'],
         	
         	['attribute' => 'Participants', 'content' => 'col_participants'],
         	['attribute' => 'Relation', 'content' => 'col_relationship'],
         	['attribute' => 'G', 'content' => 'col_gender'],
         	['attribute' => 'Obs', 'content' => 'col_observers'],
         	['attribute' => 'Distance', 'content' => 'col_distance'],
         	
         	['attribute' => 'Trials', 'value' => function($model) { return 0; }, 'headerOptions' => ['style'=>'text-align:right'], 'contentOptions' => ['align'=>'right', 'width'=>'48px']],
         	['attribute' => 'Right', 'value' => function($model) { return 0; }, 'contentOptions' => ['align'=>'right', 'width'=>'48px']],
         	['attribute' => 'FB', 'value' => function($model) { return 0; }, 'contentOptions' => ['align'=>'right', 'width'=>'48px']],
         	['attribute' => 'Right', 'value' => function($model) { return 0; }, 'contentOptions' => ['align'=>'right', 'width'=>'48px']],
         	['attribute' => 'No FB', 'value' => function($model) { return 0; }, 'contentOptions' => ['align'=>'right', 'width'=>'48px']],
         	['attribute' => 'Right', 'value' => function($model) { return 0; }, 'contentOptions' => ['align'=>'right', 'width'=>'48px'] ],

       ],
        'headerRowOptions' => ['class'=>'small'],
        'rowOptions' => ['class'=>'small'],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
    ]); ?>

</div>
