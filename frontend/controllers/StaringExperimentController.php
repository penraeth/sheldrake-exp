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

    /**
     * Lists all StaringExperiment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaringExperimentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaringExperiment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$experiment = StaringExperiment::getViewAccess($id, Yii::$app->user->identity->id);
		if (!$experiment) {
			return $this->redirect(['site/index']);
		}
		
		$participant = StaringParticipant::findOne( ['user_id'=>Yii::$app->user->identity->id, 'exp_id'=>$id] );
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
    
    
    public function actionList($type='host', $status='active') {
        $experiments = StaringExperiment::getByUserId(Yii::$app->user->identity->id, $status);
		return $this->render('list', [
			'title' => ucfirst($status).' Experiments',
			'status' => $status,
			'experiments' => $experiments,
		]);
    }
    
    public function actionListByInvite() {
        $experiments = StaringExperiment::getByInvitation(Yii::$app->user->identity->id);
		return $this->render('list', [
			'title' => 'Active Invitations',
			'status' => 'active',
			'experiments' => $experiments,
		]);
    }
    
    public function actionListByParticipant() {
        $experiments = StaringExperiment::getByParticipant(Yii::$app->user->identity->id);
		return $this->render('list', [
			'title' => 'Completed Invitations',
			'status' => 'completed',
			'experiments' => $experiments,
		]);
    }
    

    /**
     * Creates a new StaringExperiment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$errorText = '';
    	$experiment = new StaringExperiment();
		$invitations = [new UserInvitation()];
        
        if (Yii::$app->request->isPost) {
			$count = count( Yii::$app->request->post('UserInvitation', []) );
			for ($i=1; $i<$count; $i++) {
				$invitations[] = new UserInvitation();
			}
			
			$errorCount = 0;
			if ( $experiment->load(Yii::$app->request->post())  &&  UserInvitation::loadMultiple($invitations, Yii::$app->request->post()) ) {
				$experiment->user_id = Yii::$app->user->identity->id;
				$transaction = Yii::$app->db->beginTransaction();
				if ( $experiment->save() ) {
					$inviteCount = 0;
					foreach ($invitations as $i=>$invitation) {
						if ($invitation['email']) {
							if ($invitation['email'] == Yii::$app->user->identity->email) {
								$errorText = 'You cannot invite yourself to join an experiement.';
							} else {
								$invitation->exp_id = $experiment->id;
								try {
									if ( $invitation->save() ) {
										$inviteCount++;
									} else {
										$errorCount++;
									}
								} catch (\yii\db\Exception $e) {
									if ($e->errorInfo[1] == 1062) {
										//pk error on duplicate entry; ignore
									} else {
										$errorCount++;
										throw new \Exception($e);
									}
								}
							}
						}
					}
				} else {
					$errorCount++;
				}
				
				if ($errorCount  ||  $inviteCount==0) {
					$transaction->rollBack();
					if (!$errorText) {
						$errorText = 'Please enter at least one valid e-mail address.';
					}
				} else {
					$transaction->commit();
					$this->sendInvites($experiment->id);
					return $this->redirect(['view', 'id' => $experiment->id]);
				}
			}
        }

		return $this->render('create', [
			'identity' => Yii::$app->user->identity,
			'experiment' => $experiment,
			'invitations' => $invitations,
			'errorText' => $errorText
		]);
    }
    
    
    private function sendInvites($id) {
		$experiment = StaringExperiment::findOne($id);
    	$invitations = $experiment->userInvitations;
    	foreach ($invitations as $invitation) {
    		$template = ($invitation['user_id'])?'inviteUser':'inviteAnon';
			$mail = Yii::$app->mailer->compose($template, ['invitation'=>$invitation])
				->setFrom('nobody@sheldrake.org')
				->setTo($invitation->email)
				->setSubject('Staring Experiment Invitation')
				->send();
			$invitation->email_status = ($mail)?1:-1;
    		$invitation->save();
    	}
    }
    
    
    public function actionCreateExperiment()
    {  
        $experiment = new StaringExperiment();
        $participant = new StaringParticipant();

        // collect and validate data
        if ($experiment->load(Yii::$app->request->post()) && $participant->load(Yii::$app->request->post()) && Model::validateMultiple([$experiment, $participant])) {
        
			$experiment->save(false); 			// save experiment first, skip validation as model is already validated
			$participant->exp_id = $model->id;	// set foreign key for experiment
			$participant->user_id = $user->id;	// set foreign key for user
			
			$loc = $user->getLocation();
			print $loc;
			return;


			$participant->save(false);			// save primary participant, skip validation as model is already validated
        }
        
		// participant could not be saved in database
		else
		{
			// display error message to user
			Yii::$app->session->setFlash('error', 
				"We couldn't sign you up, please contact us.");

			// log this error, so we can debug possible problem easier.
			Yii::error('Signup failed! 
				User '.Html::encode($user->email).' could not sign up.
				Possible causes: something strange happened while saving to database.');

			return $this->refresh();
		}
                
        return $this->render('signup', [
            'model' => $model,
        ]);     
    }

    /**
     * Updates an existing StaringExperiment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StaringExperiment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StaringExperiment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaringExperiment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaringExperiment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
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
		#if (Model::loadMultiple($invitations, Yii::$app->request->post())) {                                                                                          Model::validateMultiple($invitations)) {
#
		#	foreach ($invitations as $invitation) {
#
				//Try to save the models. Validation is not needed as it's already been done.
		#		$invitation->save(false);

		#	}
		#	}
        return $this->redirect('view');
    }
}
