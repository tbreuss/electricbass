<?php

namespace app\models;

use yii\db\ActiveRecord;

final class Page extends ActiveRecord
{
    public static function findByUrl(string $url): ?self
    {
        return self::find()
            ->where(['url' => $url])
            ->one();
    }
}
