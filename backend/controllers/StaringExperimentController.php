<?php

namespace backend\controllers;

use Yii;
use common\models\StaringExperiment;
use common\models\StaringParticipant;
use common\models\StaringTrial;
use common\models\UserInvitation;
use frontend\models\StaringExperimentSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * StaringExperimentController implements the CRUD actions for StaringExperiment model.
 */
class StaringExperimentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }



//------------------------------------------------------------------------------------------------//
// PUBLIC ACTIONS
//------------------------------------------------------------------------------------------------//

    
    /**
     * Displays a single StaringExperiment model.
     * @param integer $id
     */
    public function actionView($id,$user_id)
    {
		$experiment = StaringExperiment::getViewAccess($id, $user_id);
		if (!$experiment) {
			return $this->redirect(['site/index']);
		}
		
		$participant = StaringParticipant::findOne( ['user_id'=>$user_id, 'exp_id'=>$id] );
		if (!$participant) {
			$participant = new StaringParticipant();
		}
		
		$trials = [];
		if ($experiment->datecompleted) {
			$trials = StaringTrial::find()->where( ['exp_id'=>$id] )->all();
		}
		
		return $this->render('view', [
			'experiment' => $experiment,
			'participant' => $participant,
			'invitations' => $experiment->userInvitations,
			'trials' => $trials,
			'host' => $experiment->host
		]);
    }
    
}
