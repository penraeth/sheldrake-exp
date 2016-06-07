<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "staring_trial".
 *
 * @property integer $exp_id
 * @property integer $trial
 * @property string $created_at
 * @property integer $observers
 * @property integer $judgment
 * @property integer $feedback
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

	public function behaviors()
	{
		return [[
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => false,
            'value' => new Expression('NOW()')
		]];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_id', 'trial','observers','judgment','feedback'], 'required'],
            [['exp_id', 'trial', 'observers', 'judgment','feedback'], 'integer'],
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
            'feedback' => 'Feedback',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExp()
    {
        return $this->hasOne(StaringExperiment::className(), ['id' => 'exp_id']);
    }

	public static function getByExperimentId($exp_id, $count=false) {
		$query = static::find()->where(['exp_id' => $exp_id]);
		if ($count) {
			return $query->count();
		} else {
			return $query->all();
		}
	} 
}
