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
	public $hostName;
	public $hostGender;
	public $relations;
	public $genders;
	public $distances;

    public function rules()
    {
        return [
            [['name', 'hostName', 'hostGender', 'relations', 'genders', 'distances'], 'string'],
            [['name', 'hostName', 'hostGender', 'relations', 'genders', 'distances'], 'safe'],
        ];
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
        $query = StaringExperiment::find();
        $query->where('datecompleted IS NOT NULL');
        $query->andWhere('result_observers > 0');
        $query->joinWith('host host');
        $query->with(['staringParticipants', 'staringParticipants.user']);
        $query->with('staringTrials');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->setSort([
			'defaultOrder' => ['datecompleted'=>SORT_DESC],
        	'attributes' => [
        		'datecompleted' => [
					'default' => SORT_DESC
        		],
        		'name',
            	'hostName' => [
					'asc' => ['host.first_name' => SORT_ASC, 'host.last_name' => SORT_ASC],
					'desc' => ['host.first_name' => SORT_DESC, 'host.last_name' => SORT_DESC],
					'default' => SORT_ASC
            	],
            	'hostGender' => [
					'asc' => ['host.gender' => SORT_ASC],
					'desc' => ['host.gender' => SORT_DESC],
					'default' => SORT_ASC
            	],
            ]
        ]);
        $this->load($params);


		// FILTERS
		if ($this->name) {
			$query->andFilterWhere(['like', 'staring_experiment.name', $this->name]);
		}
		if ($this->hostName) {
			$query->andFilterWhere(['like', 'CONCAT(host.first_name, " ", host.last_name)', $this->hostName]);
		}
		if ($this->hostGender >= 0) {
			$query->andFilterWhere(['host.gender' => $this->hostGender]);
		}
		if ($this->relations != 0) {
			$query->andFilterWhere(['result_relations' => $this->relations]);
		}
		if ($this->genders != '') {
			$query->andFilterWhere(['result_genders' => $this->genders]);
		}
		if ($this->distances != 0) {
			$query->andFilterWhere(['result_distances' => $this->distances]);
		}
        
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
}
