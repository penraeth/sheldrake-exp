<?php

namespace frontend\controllers;

use Yii;
use common\models\StaringExperiment;
use common\models\StaringTrial;
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
   		return(['message'=>'ok']);
    }

    public function actionCompleteExperiment($id, $key) {
    	$this->verifyCall($id, $key);
    	if (!$this->experiment->datecompleted) {
    		$this->experiment->datecompleted = new Expression('NOW()');
    		$this->experiment->save();
    	} else {
 			Yii::$app->response->statusCode = 202;
   		}
   		return(['message'=>'ok']);
    }
    
    
    public function actionGetNextTrial($id, $key) {
    	$this->verifyCall($id, $key);
    	$count = StaringTrial::getByExperimentId($id, true) + 1;
    	return(['message'=>'ok','next' => $count]);
    }
    
    
    public function actionLogTrial($id, $key) {
    	$this->verifyCall($id, $key);
    	$current = StaringTrial::getByExperimentId($id, true) + 1;
    	$trial = new StaringTrial();
    	
        if ( $trial->load(Yii::$app->request->post(), '') ) {
        	if ($trial->trial < 1  ||  $trial->trial > $current) {
	 			Yii::$app->response->statusCode = 406;
        		Yii::$app->response->statusText = "Trial out of range (1-{$current})";
	 			return;
        	}
        	
        	$trial->exp_id = $id;
        	try {
        		if ( !$trial->save() ) {
	 				Yii::$app->response->statusCode = 500;
        			Yii::$app->response->statusText = $model->getFirstError();
	 				return;
	 			}
			} catch (\yii\db\Exception $e) {
				if ($e->errorInfo[1] == 1062) {
					//pk error on duplicate entry; ignore
	 				Yii::$app->response->statusCode = 202;
    				return(['message'=>'ok','next' => $current]);
    				return;
				} else {
	 				Yii::$app->response->statusCode = 501;
					Yii::$app->response->statusText = $e->getMessage();
					return;
				}
        	}
        } else {
 			Yii::$app->response->statusCode = 400;
 			Yii::$app->response->statusText = 'Invalid post data';
 			return;
        }
        
        $current++;
    	return(['message'=>'ok','next' => $current]);
   }
    
}
