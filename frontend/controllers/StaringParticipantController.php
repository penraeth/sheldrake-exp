<?php

namespace frontend\controllers;

use Yii;
use app\models\StaringParticipant;
use app\models\StaringParticipantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * StaringParticipantController implements the CRUD actions for StaringParticipant model.
 */
class StaringParticipantController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    /** 
          -----> NOT NEEDED ONCE RTC IS IN PLACE
          
     * Displays a single StaringParticipant model.
     * @param integer $user_id
     * @param integer $exp_id
     * @return mixed
     */
    public function actionView($user_id, $exp_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $exp_id),
        ]);
    }
	
	
    /**
     * Creates a new StaringParticipant model.
     * If creation is successful, the browser will be redirected to the RTC system
     * @return mixed
     */
    public function actionCreate()
    {
       	$model = new StaringParticipant();
       	
       	/* in this case, db entry may already exist */
        if ( Yii::$app->request->isPost ) {
			$model = StaringParticipant::findOne( ['user_id'=>Yii::$app->user->identity->id, 'exp_id'=>Yii::$app->request->post('StaringParticipant')['exp_id']] );
			if (!$model) {
       			$model = new StaringParticipant();
			}
   			$model->load(Yii::$app->request->post());
			$model->user_id = Yii::$app->user->identity->id;
			$model->latitude = Yii::$app->session['latitude'];
			$model->longitude = Yii::$app->session['longitude'];
			$model->ipaddress = $_SERVER['REMOTE_ADDR'];
			
        	if ($model->save()) {
            	return $this->redirect(['view', 'user_id' => $model->user_id, 'exp_id' => $model->exp_id]);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

}
