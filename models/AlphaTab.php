<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $uid
 * @property string $title
 * @property ?string $subtitle
 * @property string $notation
 * @property int $instrument
 * @property int $option_group
 * @property ?array $options
 */
final class AlphaTab extends ActiveRecord
{
    public function findById(int $id): ?AlphaTab
    {
        return self::find()->where(['id' => $id, 'deleted' => null])->one();
    }

    public function findByUid(string $uid): ?AlphaTab
    {
        return self::find()->where(['uid' => $uid, 'deleted' => null])->one();
    }
}
