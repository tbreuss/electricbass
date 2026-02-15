<?php

namespace app\feature\links\models;

use yii\db\ActiveRecord;

/**
 * @property string $title
 * @property string $category
 * @property string $website
 * @property string $countryCode
 */
class Links extends ActiveRecord
{
    static function tableName(): string
    {
        return '{{website}}';
    }
}
