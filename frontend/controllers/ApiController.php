<?php

namespace frontend\controllers;

use Yii;
use app\models\StaringExperiment;
use app\models\StaringTrial;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;


class ApiController extends Controller
{
	private $experiment = null;
    public $enableCsrfValidation = false;
	
    public function init() {
		$this->layout = false;
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }
        
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'*'	=> ['post'],
				],
			],
		];
	}
	
	private function verifyCall($id, $key) {
		$this->experiment = StaringExperiment::findOne(['id'=>$id, 'apiKey'=>$key]);
		if (!$this->experiment) {
			Yii::$app->response->statusCode = 401;
			print 'invalid api parameters';
			Yii::$app->end();
		}
	}


    public function actionStartExperiment($id, $key) {
    	$this->verifyCall($id, $key);
    	if (!$this->experiment->datestarted) {
    		$this->experiment->datestarted = new Expression('NOW()');
    		$this->experiment->save();
    	} else {
 			Yii::$app->response->statusCode = 202;
   		}
    }

    public function actionCompleteExperiment($id, $key) {
    	$this->verifyCall($id, $key);
    	if (!$this->experiment->datecompleted) {
    		$this->experiment->datecompleted = new Expression('NOW()');
    		$this->experiment->save();
    	} else {
 			Yii::$app->response->statusCode = 202;
   		}
    }
    
    public function actionLogTrial($id, $key) {
    	$this->verifyCall($id, $key);
    	$trial = new StaringTrial();
    	
        if ( $trial->load(Yii::$app->request->post(), '') ) {
        	$trial->exp_id = $id;
        	try {
        		if ( !$trial->save() ) {
        			print_r($model->getErrors());
	 				Yii::$app->response->statusCode = 500;
	 			}
			} catch (\yii\db\Exception $e) {
				if ($e->errorInfo[1] == 1062) {
					//pk error on duplicate entry; ignore
	 				Yii::$app->response->statusCode = 202;
				} else {
					print $e->getMessage();
	 				Yii::$app->response->statusCode = 501;
				}
        	}
        } else {
        	print 'invalid post data';
 			Yii::$app->response->statusCode = 400;
        }
    }
    
}
