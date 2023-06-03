<?php

namespace app\models;

use yii\db\ActiveRecord;

final class AdvertisementBadWord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{advertisement_bad_word}}';
    }

    public static function findBadWords(): array
    {
        $rows = self::find()
            ->where([
                'deleted' => 0,
            ])
            ->asArray()
            ->all();

        return array_column($rows, 'word');
    }
}
