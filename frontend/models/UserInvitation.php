<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_invitation".
 *
 * @property string $email
 * @property integer $exp_id
 *
 * @property StaringExperiment $exp
 */
class UserInvitation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_invitation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'filter', 'filter' => 'trim'],
            [['email'], 'required'],
            [['email'], 'string', 'max' => 255],
            ['email', 'email']
        ];
    }
    
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExp()
    {
        return $this->hasOne(StaringExperiment::className(), ['id' => 'exp_id']);
    }
}
