<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "staring_participant".
 *
 * @property integer $user_id
 * @property integer $exp_id
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
            [['user_id', 'exp_id', 'observers', 'relationship'], 'required'],
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
	public static function getByUserId($id, $type, $count=false) {
		$query = static::find()->joinWith('staringExperiment')->where(['staring_participant.user_id' => $id]);
		switch ($type) {
			case 'active': $query->andWhere('staring_experiment.datecompleted IS NULL'); break;
			case 'completed': $query->andWhere('staring_experiment.datecompleted IS NOT NULL'); break;
		}
		if ($count) {
			return $query->count();
		} else {
			return $query->all();
		}
	} 

	public static function getExpResults($id) {
		$query = static::find()->joinWith('user');
		$query->where(['staring_participant.exp_id' => $id]);
		$query->andWhere('staring_participant.relationship > 0');
		$query->andWhere('staring_participant.status > 0');
		return $query->all();
	} 



    public function getStaringExperiment()
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
