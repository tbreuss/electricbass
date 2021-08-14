<?php

namespace app\controllers;

use yii\web\Controller;

class ToolController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMusicpaper()
    {
        return $this->render('musicpaper');
    }

    public function actionMetronome()
    {
        return $this->render('metronome');
    }

    public function actionDrumgroove()
    {
        return $this->render('drumgroove');
    }

}
