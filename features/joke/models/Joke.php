<?php

namespace app\features\joke\models;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property string $joke
 */
final class Joke extends ActiveRecord
{
    public static function getActiveDataProvider(int $page, string $sort): ActiveDataProvider
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
            'defaultOrder' => str_starts_with($sort, '-') ? [substr($sort, 1) => SORT_DESC] : [$sort => SORT_ASC],
        ]);

        $query = Joke::find()
            ->select('id, joke, created')
            ->where(['deleted' => null])
            ->orderBy($sort->orders);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [1, 1000],
                'defaultPageSize' => 1000,
            ],
            'sort' => $sort,
        ]);
    }

    public static function findOneRandom(int $maxStringLength = 100): ?Joke
    {
        return self::find()
            ->orderBy(new Expression('rand()'))
            ->where('LENGTH(joke) < :length', ['length' => $maxStringLength])
            ->limit(1)
            ->one();
    }
}
