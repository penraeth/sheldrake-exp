<?php

namespace common\models;

use Yii;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "staring_experiment_view".
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
class StaringExperimentView extends \yii\db\ActiveRecord
{
	public $row_totals;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staring_experiment_view';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    public function getStaringParticipants() {
        return $this->hasMany(StaringParticipant::className(), ['exp_id' => 'id'])->onCondition(['>', 'relationship', 0]);
    }
}
