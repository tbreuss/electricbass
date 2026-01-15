<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $notation
 * @property ?array $options
 * @property ?string $tablature
 */
final class PlayAlong extends ActiveRecord
{
    public function findById(int $id): ?PlayAlong
    {
        return self::find()->where(['id' => $id, 'deleted' => null])->one();
    }

    public function findByUid(string $uid): ?PlayAlong
    {
        return self::find()->where(['uid' => $uid, 'deleted' => null])->one();
    }
}
