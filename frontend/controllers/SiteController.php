<?php
namespace frontend\controllers;

use common\models\User;
use common\models\LoginForm;
use frontend\models\AccountActivation;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\StaringExperiment;
use yii\helpers\Html;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Cookie;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, logging users in and out,
 * sign up and account activation, password reset.
 */
class SiteController extends Controller
{
	
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     
     no idea why this was below, but I ran into trouble with it
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'signup'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Declares external actions for the controller.
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
   
   
    
//------------------------------------------------------------------------------------------------//
// STATIC PAGES
//------------------------------------------------------------------------------------------------//

    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionIndex()
    {	
    
		$cookies = Yii::$app->request->cookies;
		
		if (!Yii::$app->user->isGuest) 
        {
        	$data = [];
        	$data['host_open'] = StaringExperiment::getByUserId(Yii::$app->user->identity->id, 'active');
        	$data['host_done'] = StaringExperiment::getByUserId(Yii::$app->user->identity->id, 'completed');
        	$data['guest_open'] = StaringExperiment::getByInvitation(Yii::$app->user->identity->id);
        	$data['guest_done'] = StaringExperiment::getByParticipant(Yii::$app->user->identity->id);

	       	return $this->render('index', [
				'data' => $data,
			]);
        } else {
        
			if (isset($cookies['haslogin'])) {
				$this->redirect(array('/site/login'));
			} else {
				$this->redirect(array('/site/signup'));
			}
    	}
        
    }
    
    
    public function actionTest() {
		return $this->render('test');
   }



//------------------------------------------------------------------------------------------------//
// LOG IN / LOG OUT / PASSWORD RESET
//------------------------------------------------------------------------------------------------//

    /**
     * Logs in the user if his account is activated,
     * if not, displays appropriate message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        $model = new LoginForm();

        // now we can try to log in the user
        if ($model->load(Yii::$app->request->post()) && $model->login()) 
        {
            return $this->goBack();
        }
        // some other errors have happened
        else
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(array('/site/login'));
    }

/*----------------*
 * PASSWORD RESET *
 *----------------*/

    /**
     * Sends email that contains link for password reset action.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->sendEmail()) 
            {
                Yii::$app->session->setFlash('success', 
                    'Please check your email for further instructions.');
                return $this->goHome();
            } 
            else 
            {
                Yii::$app->session->setFlash('danger', 
                    'Sorry, we were unable to reset your password.');
                 return $this->goHome();
           }
        }
        else
        {
            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param  string $token Password reset token.
     * @return string|\yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try 
        {
            $model = new ResetPasswordForm($token);
        } 
        catch (InvalidParamException $e) 
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) 
            && $model->validate() && $model->resetPassword()) 
        {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }
        else
        {
            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }       
    }    

//------------------------------------------------------------------------------------------------//
// SIGN UP
//------------------------------------------------------------------------------------------------//

    /**
     * Signs up the user.
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {  
    
        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }
    	
        $model = new SignupForm();
        
        // if a get request and email address is passed in
        if (Yii::$app->request->isGet  &&  Yii::$app->request->get('email')) {
        	$model->email = Yii::$app->request->get('email');
        	
        // or if a post and its validated
        } else if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // try to save user data in database
            if ($user = $model->signup()) 
            {
                // if user is active he will be logged in automatically ( this will be first user )
                if ($user->status === User::STATUS_ACTIVE)
                {
                    if (Yii::$app->getUser()->login($user)) 
                    {
                        return $this->goHome();
                    }
                }
            }
            // user could not be saved in database
            else
            {
                // display error message to user
                Yii::$app->session->setFlash('danger', 
                    "We couldn't sign you up, please contact us.");

                // log this error, so we can debug possible problem easier.
                Yii::error('Signup failed! 
                    User '.Html::encode($user->email).' could not sign up.
                    Possible causes: something strange happened while saving to database.');

                return $this->refresh();
            }
        }
                
        return $this->render('signup', [
            'model' => $model,
        ]);     
    }

}
