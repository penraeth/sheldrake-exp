<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StaringParticipant */

$this->title = 'Update Staring Participant: ' . ' ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Staring Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'exp_id' => $model->exp_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staring-participant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
