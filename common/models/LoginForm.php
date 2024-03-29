<?php
namespace common\models;

use yii\base\Model;
use Yii;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $compatibility;
    public $latitude;
    public $longitude;

    /**
     * @var \common\models\User
     */
    private $_user = false;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['password', 'validatePassword'],
            [['email', 'password'], 'required'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute The attribute currently being validated.
     * @param array  $params    The additional name-value pairs.
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) 
            {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return bool Whether the user is logged in successfully.
     */
    public function login()
    {
        if ($this->validate()) 
        {
        	$cookies = Yii::$app->response->cookies;

			// add a new cookie to the response to be sent, so we know they've logged in before when they return
			$cookies->add(new \yii\web\Cookie([
				'name' => 'haslogin',
				'value' => true,
				'expire' => time() + (10 * 365 * 24 * 60 * 60),
			]));
			
			// Yii::$app->session['compatibility'] = $_POST['compatibility'];
			Yii::$app->session['latitude'] = null;
			Yii::$app->session['longitude'] = null;
			Yii::$app->session['timezone'] = null;
			
			// location
			if (isset($_POST['latitude'])  &&  $_POST['latitude']!='') {
				// values passed from browser-based location services
				Yii::$app->session['latitude'] = $_POST['latitude'];
				Yii::$app->session['longitude'] = $_POST['longitude'];
			} else {
				// no data given; use IP geolocate as a backup
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
				curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2500);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 2500);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$data = json_decode(curl_exec($ch), true);
				if (isset($data['latitude'])  &&  $data['latitude']!=''  &&  $data['latitude']!==(int)0) {
					Yii::$app->session['latitude'] = $data['latitude'];
					Yii::$app->session['longitude'] = $data['longitude'];
				}
			}
			
			// timezone
			if (isset($_POST['timezone'])  &&  $_POST['timezone']!='') {
				Yii::$app->session['timezone'] = $_POST['timezone'];
			}
			
			// login with duration of 30 days
            return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
        } 
        else 
        {
            return false;
        }  
    }

    /**
     * Finds user by email
     *
     * @return User|null|static
     */
    public function getUser()
    {
        if ($this->_user === false) 
        {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

}
