<?php

namespace app\features\quiz;

use Yii;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): string
    {
        Yii::$app->response->statusCode = 410;
        $this->layout = 'empty';
        return $this->render('@app/features/quiz/views/index');
    }
}
