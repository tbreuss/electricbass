<?php

namespace app\widgets;

use app\models\Birthday as Model;
use yii\base\Widget;

final class Birthday extends Widget
{
    public function run()
    {
        $models = Model::findTodaysBirthdays();
        if (empty($models)) {
            return '';
        }
        return $this->render('birthday', [
            'models' => $models,
        ]);
    }
}
