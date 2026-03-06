<?php

namespace app\feature\metronome;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): string
    {
        $this->layout = 'empty';
        return $this->render('@app/feature/metronome/views/index');
    }
}
