<?php

namespace app\controllers;

use app\models\Joke;
use yii\web\Controller;

final class JokeController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Joke::getActiveDataProvider();
        return $this->render('index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }
}
