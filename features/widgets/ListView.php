<?php

namespace app\features\widgets;

use app\widgets\app;
use yii\base\Widget;

final class ListView extends Widget
{
    public string $ratingStyle = '';
    public string $ratingContext = '';
    /** @var app\models\Blog[]|app\features\lesson\models\Lesson[] */
    public array $models = [];

    public function run()
    {
        return $this->render('listview', [
            'ratingStyle' => $this->ratingStyle,
            'ratingContext' => $this->ratingContext,
            'models' => $this->models,
        ]);
    }
}
