<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StaringExperiment */

$this->title = 'Create Staring Experiment';
$this->params['breadcrumbs'][] = ['label' => 'Staring Experiments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staring-experiment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
