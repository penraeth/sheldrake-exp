<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StaringParticipant;

/**
 * StaringParticipantSearch represents the model behind the search form about `app\models\StaringParticipant`.
 */
class StaringParticipantSearch extends StaringParticipant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'exp_id', 'observers', 'relationship', 'status'], 'integer'],
            [['ipaddress'], 'safe'],
            [['latitude', 'longitude'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
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
        $query = StaringParticipant::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'exp_id' => $this->exp_id,
            'observers' => $this->observers,
            'relationship' => $this->relationship,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        $query->andFilterWhere(['like', 'ipaddress', $this->ipaddress]);

        return $dataProvider;
    }
}
