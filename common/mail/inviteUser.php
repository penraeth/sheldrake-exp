<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$link = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>
<div class="password-reset">
    <p>You have been invited to join a staring experiment at sheldrake.org. Please login to your account to join.</p>
    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
