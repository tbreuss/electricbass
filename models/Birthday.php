<?php

namespace app\models;

use yii\db\ActiveRecord;

class Birthday extends ActiveRecord
{
    /**
     * @var integer
     */
    public $birth_year;

    /**
     * @var integer
     */
    public $birth_month;

    /**
     * @var integer
     */
    public $birth_day;

    public static function findTodaysBirthdays()
    {
        $models = Birthday::find()
            ->select(['*', 'SUBSTRING(birth,1,4) AS birth_year', 'SUBSTRING(birth,6,2) AS birth_month', 'SUBSTRING(birth,9,2) AS birth_day'])
            ->where('birth LIKE :birth', [':birth' => '%'.date('-m-d')])
            ->orderBy('birth_month, birth_day, birth_year')
            ->all();
        return $models;
    }

}
