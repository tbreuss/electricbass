<?php

namespace app\controllers;

use app\models\Joke;
use yii\web\Controller;

class JokeController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
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
