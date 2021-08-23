<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Video;

/**
 * VideoSearch represents the model behind the search form of `app\modules\admin\models\Video`.
 */
final class VideoSearch extends Video
{
    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            [['id', 'oid', 'width', 'height', 'comments', 'ratings', 'hits'], 'integer'],
            [['eid', 'countryCode', 'language', 'platform', 'key', 'title', 'url', 'abstract', 'text', 'tags', 'deleted', 'created', 'modified'], 'safe'],
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
        $query = Video::find();

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
            'oid' => $this->oid,
            'width' => $this->width,
            'height' => $this->height,
            'comments' => $this->comments,
            'ratings' => $this->ratings,
            'ratingAvg' => $this->ratingAvg,
            'hits' => $this->hits,
            'deleted' => $this->deleted,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'eid', $this->eid])
            ->andFilterWhere(['like', 'countryCode', $this->countryCode])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'platform', $this->platform])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        return $dataProvider;
    }
}
