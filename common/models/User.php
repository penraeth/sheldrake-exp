<?php
namespace common\models;

use common\rbac\models\Role;
use nenad\passwordStrength\StrengthValidator;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the user model class extending UserIdentity.
 * Here you can implement your custom user solutions.
 *
 * @property $role Role
 */
class User extends UserIdentity
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    public $password;

    /**
     * @var \common\rbac\models\Role
     */
    public $item_name;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['email'], 'filter', 'filter' => 'trim'],
            [['email', 'status', 'gender', 'yearofbirth'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 64],
            [['gender', 'yearofbirth'], 'integer'],
            ['email', 'email'],

            // password field is required on 'create' scenario
            ['password', 'required', 'on' => 'create'],
            // use passwordStrengthRule() method to determine password strength
            $this->passwordStrengthRule(),
                      
            ['email', 'unique', 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * Set password rule based on our setting value (Force Strong Password).
     *
     * @return array Password strength rule.
     */
    private static function passwordStrengthRule()
    {
        // get setting value for 'Force Strong Password'
        $fsp = Yii::$app->params['fsp'];

        // password strength rule is determined by StrengthValidator 
        // presets are located in: vendor/nenad/yii2-password-strength/presets.php
        $strong = [['password'], StrengthValidator::className(), 'preset'=>'normal'];

        // normal yii rule
        $normal = ['password', 'string', 'min' => 6];

        // if 'Force Strong Password' is set to 'true' use $strong rule, else use $normal rule
        return ($fsp) ? $strong : $normal;
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'password' => Yii::t('app', 'Password'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'yearofbirth' => 'Yearofbirth',
            'item_name' => Yii::t('app', 'Role'),
        ];
    }

	public function getGenderLabel()
	{
	   return $this->gender == 1 ? 'male' : 'female';
	}


    /**
     * Relation with Role model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        // User has_one Role via Role.user_id -> id
        return $this->hasOne(Role::className(), ['user_id' => 'id']);
    }

    /**
     * Relation with Article model.
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['user_id' => 'id']);
    }
    
    /**
     * Relation with participants model
     */
    public function getStaringParticipants()
    {
        return $this->hasMany(StaringParticipant::className(), ['user_id' => 'id']);
    }

    /**
     * Relation with experiments model
     */
    public function getExps()
    {
        return $this->hasMany(StaringExperiment::className(), ['id' => 'exp_id'])->viaTable('staring_participant', ['user_id' => 'id']);
    }

//------------------------------------------------------------------------------------------------//
// USER FINDERS
//------------------------------------------------------------------------------------------------//

    
    /**
     * Finds user by email.
     *
     * @param  string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    } 

    /**
     * Finds user by password reset token.
     *
     * @param  string $token Password reset token.
     * @return null|static
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) 
        {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by account activation token.
     *
     * @param  string $token Account activation token.
     * @return static|null
     */
    public static function findByAccountActivationToken($token)
    {
        return static::findOne([
            'account_activation_token' => $token,
            'status' => User::STATUS_NOT_ACTIVE,
        ]);
    }

    /**
     * Checks to see if the given user exists in our database.
     * NOTE: used in LoginForm model.
     *
     * @param  string $email
     * @param  string $password
     * @param  string $scenario
     * @return bool|static
     */
    public static function userExists($email, $password, $scenario)
    {
        
        if ($user = static::findOne(['email' => $email]))
        {
            if ($user->validatePassword($password))
            {
                return $user;
            }
            else
            {
                return false; // invalid password
            }            
        }
        else
        {
            return false; // invalid email
        }
    }
  
//------------------------------------------------------------------------------------------------//
// HELPERS
//------------------------------------------------------------------------------------------------//

    /**
     * Returns the user status in nice format.
     *
     * @param  null|integer $status Status integer value if sent to method.
     * @return string               Nicely formatted status.
     */
    public function getStatusName($status = null)
    {
        $status = (empty($status)) ? $this->status : $status ;

        if ($status === self::STATUS_DELETED)
        {
            return "Deleted";
        }
        elseif ($status === self::STATUS_NOT_ACTIVE)
        {
            return "Inactive";
        }
        else
        {
            return "Active";
        }
    }

    /**
     * Returns the array of possible user status values.
     *
     * @return array
     */
    public function getStatusList()
    {
        $statusArray = [
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_NOT_ACTIVE => 'Inactive',
            self::STATUS_DELETED    => 'Deleted'
        ];

        return $statusArray;
    }

    /**
     * Returns the role name ( item_name )
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->role->item_name;
    }

    /**
     * Generates new password reset token.
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds out if password reset token is valid.
     * 
     * @param  string $token Password reset token.
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) 
        {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
}
