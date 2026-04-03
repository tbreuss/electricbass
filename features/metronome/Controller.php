<?php

namespace app\features\metronome;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): string
    {
        $this->layout = 'empty';
        return $this->render('@app/features/metronome/views/index');
    }
}
