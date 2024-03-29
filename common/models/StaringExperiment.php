<?php

namespace common\models;

use Yii;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
	public $row_totals;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staring_experiment';
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
            [['name'], 'filter', 'filter' => 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32],
        ];
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
	public static function getByUserId($id, $type='active', $count=false) {
		$query = static::find()->where(['user_id' => $id]);
		switch ($type) {
			case 'active': $query->andWhere('datecompleted IS NULL'); break;
			case 'completed': $query->andWhere('datecompleted IS NOT NULL'); break;
		}
		$query->orderBy('created_at DESC');
		if ($count) {
			return $query->count();
		} else {
			return $query->all();
		}
	}

	public static function getByInvitation($id, $count=false) {
		$query = static::find()->joinWith('userInvitations')->where(['user_invitation.user_id' => $id]);
		$query->andWhere('datecompleted IS NULL');
		if ($count) {
			return $query->count();
		} else {
			return $query->all();
		}
	}
	
	public static function getByParticipant($id, $count=false) {
		$query = static::find()->joinWith('staringParticipants')->where(['staring_participant.user_id' => $id]);
		$query->andWhere('relationship > 0');
		$query->andWhere('datecompleted IS NOT NULL');
		if ($count) {
			return $query->count();
		} else {
			return $query->all();
		}
	}
	
	public static function getViewAccess($id, $user_id) {
		$query = static::find()->joinWith('userInvitations')->where(['staring_experiment.id' => $id]);
		$query->andFilterWhere(['or', ['staring_experiment.user_id' => $user_id], ['user_invitation.user_id' => $user_id]]);
		return $query->one();
	}
     
     
    public function getStaringParticipants() {
        return $this->hasMany(StaringParticipant::className(), ['exp_id' => 'id'])->onCondition(['>', 'relationship', 0]);
    }
    
    public function getSubject() {
        return $this->hasOne(StaringParticipant::className(), ['exp_id' => 'id'])->onCondition(['relationship' => 0]);
    }
    

    public function getUserInvitations()
    {
        return $this->hasMany(UserInvitation::className(), ['exp_id' => 'id']);
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('staring_participant', ['exp_id' => 'id']);
    }
    public function getHost() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getHostName() {
    	return $this->host->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaringTrials()
    {
        return $this->hasMany(StaringTrial::className(), ['exp_id' => 'id']);
    }
}
