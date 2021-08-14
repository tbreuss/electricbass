<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Lesson;

/**
 * LessonSearch represents the model behind the search form of `app\modules\admin\models\Lesson`.
 */
class LessonSearch extends Lesson
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'autosort', 'hideFoto', 'comments', 'ratings', 'hits', 'featured', 'deleted'], 'integer'],
            [['path', 'navtitle', 'title', 'url', 'abstract', 'text', 'tags', 'renderer', 'fotos', 'created', 'modified'], 'safe'],
            [['ratingAvg'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Lesson::find();

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
            'autosort' => $this->autosort,
            'hideFoto' => $this->hideFoto,
            'comments' => $this->comments,
            'ratings' => $this->ratings,
            'ratingAvg' => $this->ratingAvg,
            'hits' => $this->hits,
            'featured' => $this->featured,
            'deleted' => $this->deleted,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'navtitle', $this->navtitle])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'renderer', $this->renderer])
            ->andFilterWhere(['like', 'fotos', $this->fotos]);

        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
