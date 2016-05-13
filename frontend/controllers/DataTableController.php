<?php

namespace frontend\controllers;

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
class DataTableController extends Controller
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

    public function actionList($type='host', $status='active') {
        $experiments = StaringExperiment::getByUserId(Yii::$app->user->identity->id, $status);
		return $this->renderPartial('list', [
			'title' => ucfirst($status).' Experiments',
			'status' => $status,
			'experiments' => $experiments,
		]);
    }
    

    public function actionListByInvite() {
        $experiments = StaringExperiment::getByInvitation(Yii::$app->user->identity->id);
		return $this->renderPartial('list', [
			'title' => 'Active Invitations',
			'status' => 'active',
			'experiments' => $experiments,
		]);
    }
    

    public function actionListByParticipant() {
        $experiments = StaringExperiment::getByParticipant(Yii::$app->user->identity->id);
		return $this->renderPartial('list', [
			'title' => 'Completed Invitations',
			'status' => 'completed',
			'experiments' => $experiments,
		]);
    }

}
