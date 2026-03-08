<?php

namespace app\features\quiz;

use Yii;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): string
    {
        if (!YII_ENV_DEV) {
            throw new GoneHttpException();
        }

        if (Yii::$app->request->headers->has('HX-Request')) {
            return $this->renderPartial('test');
        }
        $this->layout = 'empty';
        return $this->render('@app/features/quiz/views/index');
    }
}
