<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 
    'token' => $user->password_reset_token]);
?>

Dear <?= Html::encode($user->first_name) ?>,

Follow this link below to reset your password:

<?= Html::a('Please, click here to reset your password.', $resetLink) ?>
