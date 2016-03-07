<?php
namespace frontend\models;

use common\models\User;
use common\rbac\helpers\RbacHelper;
use nenad\passwordStrength\StrengthValidator;
use yii\base\Model;
use Yii;

/**
 * Model representing  Signup Form.
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $status;
    public $first_name;
    public $last_name;
    public $yearofbirth;
    public $gender;


    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 
                'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            // use passwordStrengthRule() method to determine password strength
            $this->passwordStrengthRule(),

            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            // status has to be integer value in the given range. Check User model.
            ['status', 'in', 'range' => [User::STATUS_NOT_ACTIVE, User::STATUS_ACTIVE]],
            
            [['first_name', 'last_name'], 'string', 'max' => 64],
            
            [['gender', 'yearofbirth'], 'required'],
            [['gender', 'yearofbirth'], 'integer'],
            [['yearofbirth'], 'string', 'max' => 4],
            
        ];
    }

    /**
     * Set password rule based on our setting value (Force Strong Password).
     *
     * @return array Password strength rule
     */
    private function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/nenad/yii2-password-strength/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // use normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
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
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'gender' => Yii::t('app', 'Gender'),
            'yearofbirth' => Yii::t('app', 'Year of birth YYYY'),
        ];
    }

    /**
     * Signs up the user.
     *
     * @return User|null The saved model or null if saving fails.
     */
    public function signup()
    {
        $user = new User();

        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = $this->status;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->gender = $this->gender;
        $user->yearofbirth = $this->yearofbirth;
        
		if (isset($_POST['latitude'])) {
			Yii::$app->session['latitude'] = $_POST['latitude'];
			Yii::$app->session['longitude'] = $_POST['longitude'];
		}

        // if user is saved and role is assigned return user object
        return $user->save() && RbacHelper::assignRole($user->getId()) ? $user : null;
    }
}
