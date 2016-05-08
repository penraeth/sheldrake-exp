<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StaringParticipant */

$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Staring Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staring-participant-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'user_id' => $model->user_id, 'exp_id' => $model->exp_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id, 'exp_id' => $model->exp_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'exp_id',
            'observers',
            'relationship',
            'status',
            'latitude',
            'longitude',
            'ipaddress',
        ],
    ]) ?>

</div>
