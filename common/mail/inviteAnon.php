<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$link = Yii::$app->urlManager->createAbsoluteUrl(['site/signup', 'email'=>$invitation['email']]);
$text = Yii::$app->urlManager->createAbsoluteUrl(['site/signup']);
?>
<div class="password-reset">
    <p>You have been invited to join a staring experiment at sheldrake.org. Please use the link below to create an account.</p>
    <p><?= Html::a($text, $link) ?></p>
</div>
