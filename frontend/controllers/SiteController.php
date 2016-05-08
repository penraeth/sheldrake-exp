<?php
namespace frontend\controllers;

use common\models\User;
use common\models\LoginForm;
use frontend\models\AccountActivation;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use app\models\StaringExperiment;
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
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
        	$badges = [];
        	$badges['host_open'] = StaringExperiment::getByUserId(Yii::$app->user->identity->id, 'active', true);
        	$badges['host_done'] = StaringExperiment::getByUserId(Yii::$app->user->identity->id, 'completed', true);
        	$badges['guest_open'] = StaringExperiment::getByInvitation(Yii::$app->user->identity->id, true);
        	$badges['guest_done'] = StaringExperiment::getByInvitation(Yii::$app->user->identity->id, true);
        	return $this->render('index', [
				'badges' => $badges
			]);
        } else {
        
			if (isset($cookies['haslogin'])) {
				$this->redirect(array('/site/login'));
			} else {
				$this->redirect(array('/site/signup'));
			}
    	}
        
    }

    /**
     * Displays the about static page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays the about static page.
     *
     * @return string
     */
    public function actionIncompatible()
    {
        return $this->render('incompatible');
    }

    /**
     * Displays the contact static page and sends the contact email.
     *
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->contact(Yii::$app->params['adminEmail'])) 
            {
                Yii::$app->session->setFlash('success', 
                    'Thank you for contacting us. We will respond to you as soon as possible.');
            } 
            else 
            {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } 
        
        return $this->render('contact', [
            'model' => $model,
        ]);
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
                    'Check your email for further instructions.');

                return $this->goHome();
            } 
            else 
            {
                Yii::$app->session->setFlash('error', 
                    'Sorry, we are unable to reset password for email provided.');
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
        $model = new SignupForm();
        
        if (Yii::$app->request->isGet  &&  Yii::$app->request->get('email')) {
        	$model->email = Yii::$app->request->get('email');
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
                Yii::$app->session->setFlash('error', 
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
