<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StaringExperiment;

/**
 * DocumentSearch represents the model behind the search form about `\common\models\Document`.
 */
class ExperimentSearch extends StaringExperiment
{

    public function rules()
    {
        return [];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
     
     
    public function search($params)
    {
        $query = StaringExperiment::find()->where('datecompleted IS NOT NULL');
        $query->with('subject');
        $query->with('subject.user');
        $query->joinWith('staringParticipants')->andWhere('staring_participant.relationship > 0');
        $query->joinWith('staringParticipants.user');
        #$query->joinWith(['customer']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
