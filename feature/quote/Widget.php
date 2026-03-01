<?php

namespace app\feature\quote;

use app\feature\quote\models\Quote;

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
