<?php

namespace app\models;

use yii\db\ActiveRecord;

final class AdvertisementBadWord extends ActiveRecord
{
    public static function tableName()
    {
        return '{{advertisement_bad_word}}';
    }

    /**
     * @return string[]
     */
    public static function findBadWords(): array
    {
        $rows = self::find()
            ->where([
                'deleted' => 0,
            ])
            ->asArray()
            ->all();

        return array_map(
            function (string $value): string {
                $value = mb_convert_encoding($value, 'ISO-8859-1', 'UTF-8');
                return mb_strtolower($value, 'UTF-8');
            },
            array_column($rows, 'word')
        );
    }
}
