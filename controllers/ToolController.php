<?php

namespace app\controllers;

use yii\web\Controller;

final class ToolController extends Controller
{
    public function actionMusicpaper(): string
    {
        $this->layout = 'onecol';
        return $this->render('musicpaper');
    }
}
