<?php

namespace app\widgets;

use app\models\Quote as QuoteModel;
use yii\base\Widget;

final class Quote extends Widget
{

    public function run()
    {
        $model = QuoteModel::findOneRandom();
        return $this->render('quote', [
            'model' => $model,
        ]);
    }
}
