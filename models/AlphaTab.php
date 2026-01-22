<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $uid
 * @property string $title
 * @property ?string $subtitle
 * @property string $alpha_tex
 * @property int $instrument
 * @property int $options_group
 * @property ?array $options
 * @property ?AlphaDrums $drums
 * @property string $time_signature
 * @property int bar_count
 */
final class AlphaTab extends ActiveRecord
{
    public function getDrums()
    {
        return $this->hasOne(AlphaDrums::class, ['id' => 'alpha_drums_id']);
    }

    public function findById(int $id): ?AlphaTab
    {
        return self::find()->where(['id' => $id, 'deleted' => null])->one();
    }

    public function findByUid(string $uid): ?AlphaTab
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
}
