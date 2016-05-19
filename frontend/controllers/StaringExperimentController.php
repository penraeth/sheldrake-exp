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



//------------------------------------------------------------------------------------------------//
// PUBLIC ACTIONS
//------------------------------------------------------------------------------------------------//

    /**
     * Displays a the StaringExperiment.
     * @param integer $id
     */
    public function actionExperiment($id)
    {
		$experiment = StaringExperiment::getViewAccess($id, Yii::$app->user->identity->id);
		if (!$experiment) {
			return $this->redirect(['site/index']);
		}
		
		if ($experiment->datecompleted) {
			return $this->redirect(['staring-experiment/view', 'id' => $experiment->id]);
		}
		
		$participant = StaringParticipant::findOne( ['user_id'=>Yii::$app->user->identity->id, 'exp_id'=>$id] );
		
		return $this->render('view', [
			'experiment' => $experiment,
			'isSubject' => ($experiment->host->id == Yii::$app->user->identity->id),
			'observers' => $participant->observers
		]);
    }
    
    /**
     * Displays a single StaringExperiment model.
     * @param integer $id
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
    
    
    /**
     * Displays multiple host records for the current user, based on given params
     * @param integer $id
     */
    public function actionList($type='host', $status='active') {
        $experiments = StaringExperiment::getByUserId(Yii::$app->user->identity->id, $status);
		return $this->render('list', [
			'title' => ucfirst($status).' Experiments',
			'status' => $status,
			'experiments' => $experiments,
		]);
    }
    
    /**
     * Displays multiple records for the current user, based on invitations
     * @param integer $id
     */
    public function actionListByInvite() {
        $experiments = StaringExperiment::getByInvitation(Yii::$app->user->identity->id);
		return $this->render('list', [
			'title' => 'Active Invitations',
			'status' => 'active',
			'experiments' => $experiments,
		]);
    }
    
    /**
     * Displays multiple records for the current user, based on partipants
     * @param integer $id
     */
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
        	# pre-load array size for invitations
			$count = count( Yii::$app->request->post('UserInvitation', []) );
			for ($i=1; $i<$count; $i++) {
				$invitations[] = new UserInvitation();
			}
			
			$errorCount = 0;
			# load experiement and all invitations at once
			if ( $experiment->load(Yii::$app->request->post())  &&  UserInvitation::loadMultiple($invitations, Yii::$app->request->post()) ) {
				$experiment->user_id = Yii::$app->user->identity->id;
				
				$transaction = Yii::$app->db->beginTransaction();
				if ( $experiment->save() ) {
					$inviteCount = 0;
					
					# attempt to save each e-mail address; returns false if validation fails
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
										# pk error on duplicate entry; ignore
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
				
				# make sure something got saved
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
    
    
    
//------------------------------------------------------------------------------------------------//
// PRIVATE ACTIONS
//------------------------------------------------------------------------------------------------//

    /**
     * Sends an email invitation.
     * Called only internally upon experiement creation
     */
    private function sendInvites($id) {
		$experiment = StaringExperiment::findOne($id);
    	$invitations = $experiment->userInvitations;
    	foreach ($invitations as $invitation) {
    		$template = ($invitation['user_id'])?'inviteUser':'inviteAnon';
			$mail = Yii::$app->mailer->compose($template, ['invitation'=>$invitation])
				->setFrom( Yii::$app->params['fromEmail'] )
				->setTo($invitation->email)
				->setSubject('You are invitied to join a Staring Experiment')
				->send();
			$invitation->email_status = ($mail)?1:-1;
    		$invitation->save();
    	}
    }
    
}
