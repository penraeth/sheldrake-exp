<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password reset request form.
 */
class PasswordResetRequestForm extends Model
{
    public $email;

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
//            ['email', 'exist',
//                'targetClass' => '\common\models\User',
//                'filter' => ['status' => User::STATUS_ACTIVE],
//                'message' => 'There is no user with such email.'
//            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool Whether the email was send.
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            $user->generatePasswordResetToken();
            if ( $user->save() ) {
                return Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                    ->setFrom(Yii::$app->params['fromEmail'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . Yii::$app->name)
                    ->send();
            } else {
            	return false;
            }
        }
        return true;
    }
}
