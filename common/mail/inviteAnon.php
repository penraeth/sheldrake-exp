<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$signuplink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup', 'email'=>$invitation['email']]);
$signuptext = Yii::$app->urlManager->createAbsoluteUrl(['site/signup']);
$explink = Yii::$app->urlManager->createAbsoluteUrl(['staring-experiment/view', 'id'=>$invitation['exp_id']]);
$experiment = Yii::$app->urlManager->createAbsoluteUrl(['staring-experiment/view', 'email'=>$invitation['email'], 'id'=>$invitation['exp_id']]);

?>

<h2 style="color:#468847">Sheldrake Staring Experiment</h2>

<div style="background-color: #dff0d8; border: 1px solid #dddddd; border-radius: 4px; color: #468847; margin: 0 0 2em; padding: 10px 15px;">
    <p><?= $hostname ?> invited you to participate in a staring detection experiment online. Please use the link below to create an account.</p>
    <p><b>Create an account:</b> <?= Html::a($signuptext, $signuplink) ?></p>
    <p>Once logged in, or if you already have an account...</p>
    <p><b>Join experiment:</b> <?= Html::a($explink, $experiment) ?></p>
    </p>
</div>

<p>
	Have the latest version of <i>Chrome</i>, <i>Firefox</i> or <i>Opera</i> and a camera? You're ready. <i>Chrome</i> on an Android device may also work. <i>IE</i> and <i>Safari</i> users need to <a href="https://skylink.io/plugin/" target="_blank">install this plugin first</a>. 
</p>
<p>
	Traditional staring experiments involve one subject and one observer, staring at the back of the subject's neck. In this experiment, one or more observers stare directly at the subject's face, using new video sharing technology. Through several trials, observers see either the subject or a random image. Eath time the subject must guess whether or not they're being stared at.
</p>
<p>
	We hope to determine if face-on staring is easier to detect or not, and if more observers enhances the effect. We're also interested in whether physical distance between subject and observer has any effect, or if emotional closeness is involved.
</p>
