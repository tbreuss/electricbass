<?php

namespace app\models;

use yii\db\ActiveRecord;

class Redirect extends ActiveRecord
{
    public static function findOneByRequestUrl(string $url): ?Redirect
    {
        return self::find()
            ->where(['from' => $url, 'deleted' => null])
            ->one();
    }
}
