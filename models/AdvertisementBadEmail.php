<?php

namespace app\models;

use yii\db\ActiveRecord;

final class AdvertisementBadEmail extends ActiveRecord
{
    public static function tableName()
    {
        return '{{advertisement_bad_email}}';
    }

    /**
     * @return string[]
     */
    public static function findRegexPatterns(): array
    {
        return self::find()
            ->select('regex_pattern')
            ->where(['deleted' => null])
            ->column();
    }
}
