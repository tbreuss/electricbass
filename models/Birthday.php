<?php

namespace app\models;

use yii\db\ActiveRecord;

final class Birthday extends ActiveRecord
{
    /**
     * @var int
     */
    public $birth_year;

    /**
     * @var int
     */
    public $birth_month;

    /**
     * @var int
     */
    public $birth_day;

    /**
     * @return Birthday[]
     */
    public static function findTodaysBirthdays(): array
    {
        return Birthday::find()
            ->select(['*', 'SUBSTRING(birth,1,4) AS birth_year', 'SUBSTRING(birth,6,2) AS birth_month', 'SUBSTRING(birth,9,2) AS birth_day'])
            ->where('birth LIKE :birth', [':birth' => '%' . date('-m-d')])
            ->orderBy('birth_month, birth_day, birth_year')
            ->all();
    }
}
