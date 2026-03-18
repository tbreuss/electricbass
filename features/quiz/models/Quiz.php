<?php

namespace app\features\quiz\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $nextId
 * @property string $title
 * @property string $widget
 * @property array $widgetOptions
 * @property ?string $deleted
 */
class Quiz extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{quiz}}';
    }

    public static function findOneByUrl(string $url): ?self
    {
        return self::find()->where(['url' => $url])->one();
    }

    public function getNext(): ActiveQuery
    {
        return $this->hasOne(Quiz::class, ['id' => 'nextId']);
    }
}
