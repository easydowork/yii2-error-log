<?php

namespace adminlog\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use adminlog\models\ErrorLog;

/**
 * Class ErrorLogSearch
 * @package adminlog\models\search
 */
class ErrorLogSearch extends ErrorLog
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'level'], 'integer'],
            [['request_uri', 'title', 'message', 'get', 'post', 'cookie', 'session', 'server'], 'string'],
            [['files'], 'number'],
            [['category'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 20],
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
        $query = ErrorLog::find()->orderBy('id desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => ['pageSize' => 15], //默认分页大小为 15
        ]);

        $dataProvider->sort = false;

        $this->load($params);

        if ( !$this->validate() ) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ip'        => $this->ip,
            'user_id'   => $this->user_id,
            'create_at' => $this->create_at,
            'category'  => $this->category,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'request_uri', $this->request_uri]);

        return $dataProvider;
    }
}
