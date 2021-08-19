<?php

namespace app\widgets;

use yii\base\Widget;

class ListView extends Widget
{
    public string $ratingStyle;
    public string $ratingContext;
    public array $models;
    public function run()
    {
        return $this->render('listview', [
            'ratingStyle' => $this->ratingStyle,
            'ratingContext' => $this->ratingContext,
            'models' => $this->models,
        ]);
    }
}
