<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property string $to
 * @property string $updated
 * @property int $count
 */
class Redirect extends ActiveRecord
{
    public static function findOneByRequestUrl(string $url): ?Redirect
    {
        return self::find()
            ->where(['from' => $url, 'deleted' => null])
            ->one();
    }
}
