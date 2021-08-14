<?php

namespace app\widgets;

use app\models\Quote as QuoteModel;
use yii\base\Widget;

class ListView extends Widget
{
    public $ratingStyle;
    public $ratingContext;
    public $models;
    public function run()
    {
        return $this->render('listview', [
            'ratingStyle' => $this->ratingStyle,
            'ratingContext' => $this->ratingContext,
            'models' => $this->models,
        ]);
    }
}
