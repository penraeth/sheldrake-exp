<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$link = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>
<div class="password-reset">
    <p>You have been invited to join a staring experiment at sheldrake.org. Please login to your account to join.</p>
    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>

<p>
	If you're using the latest version of <i>Chrome</i>, <i>Firefox</i> or <i>Opera</i> and your computer is equiped with a camera then you're ready to participate. <i>Chrome</i> on an Android device will also work. <i>IE</i> and <i>Safari</i> users need to <a href="http://skylink.io/plugin/" target="_blank">install this plugin first</a>. 
</p>
<p>
	Traditional staring experiments involve one subject and one observer, staring at the back of the subject's neck. In this experiment one or more observers stare directly at the subject's face, using new video sharing technology. Through several trials, observers are randomly shown a video of the subject, or not. The subject must guess whether he or she is being stared at.
</p>
<p>
	We hope to determine if face-on staring is easier to detect or not, and if more observers enhances the effect. We're also interested in whether physical distance between subject and obesrver has any effect, or if emotional closness is involved.
</p>
