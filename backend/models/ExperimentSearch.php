<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StaringExperimentView;

/**
 * DocumentSearch represents the model behind the search form about `\common\models\Document`.
 */
class ExperimentSearch extends StaringExperimentView
{
	//public $subject_name;
	//public $subject_gender;
	public $relations;
	public $genders;
	public $distances;

    public function rules()
    {
        return [
            [['name', 'subject_name', 'subject_gender', 'subject_age', 'relations', 'genders', 'distances', 'result_observers'], 'string'],
            [['name', 'subject_name', 'subject_gender', 'subject_age', 'relations', 'genders', 'distances', 'result_observers'], 'safe'],
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
        $query = StaringExperimentView::find();
        //$query->where('datecompleted IS NOT NULL');
        //$query->andWhere('result_observers > 0');
        //$query->joinWith('host host');
        $query->with(['staringParticipants', 'staringParticipants.user']);
        //$query->with('staringTrials');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => false
        ]);
        $dataProvider->setSort([
			'defaultOrder' => ['datecompleted'=>SORT_DESC],
        	'attributes' => [
        		'datecompleted' => [
					'default' => SORT_DESC
        		],
        		'name',
            	'subject_name' => [
					//'asc' => ['host.first_name' => SORT_ASC, 'host.last_name' => SORT_ASC],
					//'desc' => ['host.first_name' => SORT_DESC, 'host.last_name' => SORT_DESC],
					'default' => SORT_ASC
            	],
            	'subject_gender' => [
					//'asc' => ['host.gender' => SORT_ASC],
					//'desc' => ['host.gender' => SORT_DESC],
					'default' => SORT_ASC
            	],
            	'result_observers'
            ]
        ]);
        $this->load($params);


		// FILTERS
		if ($this->name) {
			$query->andFilterWhere(['like', 'staring_experiment.name', $this->name]);
		}
		if ($this->subject_name) {
			$query->andFilterWhere(['like', 'subject_name', $this->subject_name]);
		}
		if ($this->subject_gender >= 0) {
			$query->andFilterWhere(['subject_gender' => $this->subject_gender]);
		}
		if ($this->subject_age >= 0) {
			$query->andFilterWhere(['subject_age' => $this->subject_age]);
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
		if ($this->result_observers != '') {
			if (is_numeric($this->result_observers)) {
				$query->andFilterWhere(['result_observers' => $this->result_observers]);
			} else if (preg_match('/^([\>\<]\={0,1})(\d+)$/', $this->result_observers, $ref)) {
				$query->andFilterWhere([$ref[1], 'result_observers', $ref[2]]);
			} else {
				$this->result_observers = null;
			}
		}
        
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }
}
