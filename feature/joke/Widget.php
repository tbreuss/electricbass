<?php

namespace app\feature\joke;

use app\feature\joke\models\Joke;

final class Widget extends \yii\base\Widget
{
    public function run()
    {
        $model = Joke::findOneRandom();
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
