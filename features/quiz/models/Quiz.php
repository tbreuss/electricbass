<?php

namespace app\features\quiz\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $uid
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

    public static function findOneByUid(string $uid): ?self
    {
        return self::find()->where(['uid' => $uid, 'deleted' => null])->one();
    }

    public function getNextQuiz(): ActiveQuery
    {
        return $this->hasOne(Quiz::class, ['id' => 'nextId']);
    }
}
