<?php

namespace app\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use app\models\Setting;

class UserInvitationController extends \yii\web\Controller
{
    public function actionInvite()
    {
    
        //Find out how many invitations have been submitted
        $count = count(Yii::$app->request->post('Email', []));

        //Send at least one model to the form
        $invitations = [new UserInvitation()];

        //Create an array of the invitations submitted
        for($i = 1; $i < $count; $i++) {
            $invitations[] = new UserInvitation();
        }

		//Load and validate the multiple models
		if (Model::loadMultiple($invitations, Yii::$app->request->post()) &&                                                                                            Model::validateMultiple($invitations)) {

        foreach ($invitations as $invitation) {

            //Try to save the models. Validation is not needed as it's already been done.
            $invitation->save(false);

        }
        return $this->redirect('view');
    }

}
