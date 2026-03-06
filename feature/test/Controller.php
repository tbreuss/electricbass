<?php

namespace app\feature\test;

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

        return $this->render('@app/feature/test/views/index');
    }
}
