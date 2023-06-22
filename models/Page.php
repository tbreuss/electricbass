<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $abstract
 * @property string $content
 */
final class Page extends ActiveRecord
{
    public static function findByUrl(string $url): ?self
    {
        return self::find()
            ->where(['url' => $url])
            ->one();
    }
}
