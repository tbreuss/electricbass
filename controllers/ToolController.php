<?php

namespace app\controllers;

use yii\web\Controller;

final class ToolController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionMusicpaper(): string
    {
        $this->layout = 'onecol';
        return $this->render('musicpaper');
    }

    public function actionMetronome(): string
    {
        return $this->render('metronome');
    }

    public function actionDrumgroove(): string
    {
        return $this->render('drumgroove');
    }

}
