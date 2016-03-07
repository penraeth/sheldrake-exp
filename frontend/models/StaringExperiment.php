<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staring_experiment".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $datestarted
 * @property string $datecompleted
 *
 * @property StaringParticipant[] $staringParticipants
 * @property User[] $users
 * @property StaringTrial[] $staringTrials
 */
class StaringExperiment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staring_experiment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaringParticipants()
    {
        return $this->hasMany(StaringParticipant::className(), ['exp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('staring_participant', ['exp_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaringTrials()
    {
        return $this->hasMany(StaringTrial::className(), ['exp_id' => 'id']);
    }
}
