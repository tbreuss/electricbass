<?php

namespace app\features\quiz;

use app\features\quiz\models\Quiz;
use Yii;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    public $layout = 'empty';

    public function actionIndex(): string
    {
        Yii::$app->response->statusCode = 410;

        $models = Quiz::findAll(['deleted' => null]);
        return $this->render('@app/features/quiz/views/index', ['models' => $models]);
    }

    public function actionView(string $url): string
    {
        Yii::$app->response->statusCode = 410;

        $model = Quiz::findOneByUrl($url);

        if ($model === null) {
            throw new GoneHttpException();
        }

        return $this->render('@app/features/quiz/views/view', ['model' => $model]);
    }
}
