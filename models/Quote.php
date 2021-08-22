<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property string $text_en
 * @property string $author
 * @property string $text
 */
final class Quote extends ActiveRecord
{
    public static function getActiveDataProvider(): ActiveDataProvider
    {
        $sort = new Sort([
            'attributes' => [
                'author' => [
                    'asc' => ['author' => SORT_ASC],
                    'desc' => ['author' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Autor',
                ],
                'created' => [
                    'asc' => ['created' => SORT_ASC],
                    'desc' => ['created' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Erstellungsdatum',
                ],
            ],
            'defaultOrder' => [
                'author' => SORT_ASC,
            ]
        ]);

        $query = Quote::find()
            ->select('id, text_de, text_en, author')
            ->where(['deleted' => 0])
            ->orderBy($sort->orders);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200,
                'defaultPageSize' => 200,
            ],
            'sort' => $sort,
        ]);

        return $provider;
    }

    public static function findOneRandom(): ?Quote
    {
        return self::find()
            ->orderBy(new Expression('rand()'))
            ->limit(1)
            ->one();
    }

    public function getAuthor(): string
    {
        if (empty($this->author)) {
            return 'Unbekannt';
        }
        return $this->author;
    }

    public function getText(): string
    {
        if (!empty($this->text_de)) {
            return $this->text_de;
        }
        return $this->text_en;
    }

}
