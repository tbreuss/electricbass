<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\GoneHttpException;

final class SiteController extends Controller
{
    public function actionTest(): string
    {
        if (!YII_ENV_DEV) {
            throw new GoneHttpException();
        }

        if (Yii::$app->request->headers->has('HX-Request')) {
            return $this->renderPartial('test');
        }

        return $this->render('test');
    }
}
