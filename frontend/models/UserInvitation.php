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
	public static function getByUserId($id, $type='active', $count=false) {
		$query = static::find()->joinWith('staringExperiment')->where(['user_invitation.user_id' => $id])->with('staringExperiment');
		switch ($type) {
			case 'active': $query->andWhere('staring_experiment.datestarted IS NULL'); break;
			case 'completed': $query->andWhere('staring_experiment.datecompleted IS NOT NULL'); break;
		}
		if ($count) {
			return $query->count();
		} else {
			return $query->all();
		}
	} 
	

    public function getStaringExperiment()
    {
        return $this->hasOne(StaringExperiment::className(), ['id' => 'exp_id']);
    }
}
