<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StaringParticipant */

$this->title = 'Create Staring Participant';
$this->params['breadcrumbs'][] = ['label' => 'Staring Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staring-participant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
