<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaringParticipantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Staring Participants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staring-participant-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Staring Participant', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_id',
            'exp_id',
            'datejoined',
            'observers',
            'relationship',
            // 'status',
            // 'latitude',
            // 'longitude',
            // 'ipaddress',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
