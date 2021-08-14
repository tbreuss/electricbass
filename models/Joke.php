<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class Joke extends ActiveRecord
{
    /**
     * @return ActiveDataProvider
     */
    public static function getActiveDataProvider()
    {
        $sort = new Sort([
            'attributes' => [
                'joke' => [
                    'asc' => ['joke' => SORT_ASC],
                    'desc' => ['joke' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Alphabet',
                ],
                'created' => [
                    'asc' => ['created' => SORT_ASC],
                    'desc' => ['created' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Erstellungsdatum',
                ],
            ],
            'defaultOrder' => [
                'joke' => SORT_ASC,
            ]
        ]);

        $query = Joke::find()
            ->select('id, joke, created')
            ->where(['deleted' => NULL])
            ->orderBy($sort->orders);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 200],
                'defaultPageSize' => 200,
            ],
            'sort' => $sort,
        ]);

        return $provider;
    }

    /**
     * @return null|ActiveRecord
     */
    public static function findOneRandom($maxStringLength = 100)
    {
        $model = self::find()
            ->orderBy(new Expression('rand()'))
            ->where('LENGTH(joke) < :length', ['length' => $maxStringLength])
            ->limit(1)
            ->one();
        return $model;
    }

}
