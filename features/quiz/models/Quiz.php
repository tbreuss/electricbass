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

    public static function findAllByUidLike(string $like): array
    {
        return Quiz::find()
            ->where(['deleted' => null])
            ->where('uid LIKE :uid', [':uid' => $like])
            ->orderBy(['uid' => SORT_ASC])
            ->all();
    }

    public static function findOneByUid(string $uid): ?self
    {
        return self::find()->where(['uid' => $uid, 'deleted' => null])->one();
    }
}
