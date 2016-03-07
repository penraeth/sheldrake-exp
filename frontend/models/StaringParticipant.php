<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staring_participant".
 *
 * @property integer $user_id
 * @property integer $exp_id
 * @property string $datejoined
 * @property integer $observers
 * @property integer $relationship
 * @property integer $status
 * @property string $latitude
 * @property string $longitude
 * @property string $ipaddress
 *
 * @property StaringExperiment $exp
 * @property User $user
 */
class StaringParticipant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staring_participant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'exp_id'], 'required'],
            [['user_id', 'exp_id', 'observers', 'relationship', 'status'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['ipaddress'], 'string', 'max' => 200]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'observers' => 'Number of Observers',
            'relationship' => 'Relationship to Subject',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExp()
    {
        return $this->hasOne(StaringExperiment::className(), ['id' => 'exp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
