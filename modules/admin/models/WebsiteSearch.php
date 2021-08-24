<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Website;

/**
 * WebsiteSearch represents the model behind the search form of `app\modules\admin\models\Website`.
 */
final class WebsiteSearch extends Website
{
    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            [['id', 'comments', 'ratings', 'hits', 'status'], 'integer'],
            [['countryCode', 'language', 'title', 'url', 'subtitle', 'abstract', 'content', 'website', 'tags', 'latitude', 'longitude', 'geocodingAddress', 'deleted', 'created', 'modified'], 'safe'],
            [['ratingAvg'], 'number'],
        ];
    }

    /**
     * @phpstan-return array<array>
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @phpstan-param array<string, string> $params
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Website::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'comments' => $this->comments,
            'ratings' => $this->ratings,
            'ratingAvg' => $this->ratingAvg,
            'hits' => $this->hits,
            'status' => $this->status,
            'deleted' => $this->deleted,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'countryCode', $this->countryCode])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'geocodingAddress', $this->geocodingAddress]);

        return $dataProvider;
    }
}
