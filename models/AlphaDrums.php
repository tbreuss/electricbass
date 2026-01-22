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
 * @property string $time_signature
 * @property int $bar_count
 */
final class AlphaDrums extends ActiveRecord
{
    public function findByUid(string $uid): ?AlphaDrums
    {
        return self::find()->where(['uid' => $uid, 'deleted' => null])->one();
    }

    /**
     * @return array<?int, ?int> time signature tuple with numerator and denominator
     */
    public function timeSignature(): array
    {
        if ($this->time_signature === null) {
            return [null, null];
        }
        return array_map('intval', explode('/', $this->time_signature));
    }

    public function barCount(): int
    {
        return $this->bar_count;
    }
}
