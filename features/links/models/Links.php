<?php

namespace app\features\links\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property string $abstract
 * @property int $archived
 * @property string $category
 * @property string $countryCode
 * @property string $subtitle
 * @property string $title
 * @property string $website
 */
class Links extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{website}}';
    }

    public static function findByCountryCodeAndTags(string $countryCode, string $csvTags): array
    {
        return self::find()
            ->select(['title', 'subtitle', 'website', 'abstract', 'archived'])
            ->where('deleted IS NULL AND countryCode=:countryCode AND FIND_IN_SET(:csvTags, tags)', [
                'countryCode' => $countryCode,
                'csvTags' => $csvTags,
            ])
            ->orderBy('title ASC')
            ->all();
    }


    /**
     * @return string[]
     * @throws \yii\db\Exception
     */
    public static function findUrls(): array
    {
        $sql = <<<SQL
                SELECT website
            FROM website
            WHERE deleted IS NULL
        SQL;
        return Yii::$app->db->createCommand($sql)->queryColumn();
    }
}
