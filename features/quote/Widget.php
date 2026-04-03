<?php

namespace app\features\quote;

use app\features\quote\models\Quote;

final class Widget extends \yii\base\Widget
{
    public function run()
    {
        $model = Quote::findOneRandom();
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
