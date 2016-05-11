<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "staring_trial".
 *
 * @property integer $exp_id
 * @property integer $trial
 * @property string $created_at
 * @property integer $observers
 * @property integer $judgment
 *
 * @property StaringExperiment $exp
 */
class StaringTrial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staring_trial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_id', 'trial','observers','judgment'], 'required'],
            [['exp_id', 'trial', 'observers', 'judgment'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exp_id' => 'Exp ID',
            'trial' => 'Trial',
            'created_at' => 'Created At',
            'observers' => 'Observers',
            'judgment' => 'Judgment',
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
