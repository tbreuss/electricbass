<?php

namespace app\widgets;

use app\models\Joke as JokeModel;
use yii\base\Widget;

final class Joke extends Widget
{
    public function run()
    {
        $model = JokeModel::findOneRandom();
        return $this->render('joke', [
            'model' => $model,
        ]);
    }
}
