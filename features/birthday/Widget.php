<?php

namespace app\features\birthday;

use app\features\birthday\models\Birthday;

final class Widget extends \yii\base\Widget
{
    public function run()
    {
        $models = Birthday::findTodaysBirthdays();
        if (empty($models)) {
            return '';
        }
        return $this->render('widget', [
            'models' => $models,
        ]);
    }
}
