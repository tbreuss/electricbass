<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Blog;

/**
 * BlogSearch represents the model behind the search form of `app\modules\admin\models\Blog`.
 */
final class BlogSearch extends Blog
{
    /**
     * @phpstan-return array<int, array>
     */
    public function rules(): array
    {
        return [
            [['id', 'categoryId', 'hideFoto', 'comments', 'ratings', 'hits', 'featured'], 'integer'],
            [['countryCode', 'language', 'title', 'mainTag', 'url', 'movedTo', 'subtitle', 'abstract', 'text', 'tags', 'renderer', 'fotoCopyright', 'publication', 'deleted', 'created', 'modified'], 'safe'],
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
        $query = Blog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'categoryId' => $this->categoryId,
            'hideFoto' => $this->hideFoto,
            'comments' => $this->comments,
            'ratings' => $this->ratings,
            'ratingAvg' => $this->ratingAvg,
            'hits' => $this->hits,
            'featured' => $this->featured,
            'publication' => $this->publication,
            'deleted' => $this->deleted,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'countryCode', $this->countryCode])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'mainTag', $this->mainTag])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'movedTo', $this->movedTo])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'renderer', $this->renderer])
            ->andFilterWhere(['like', 'fotoCopyright', $this->fotoCopyright]);

        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
