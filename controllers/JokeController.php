<?php

namespace app\controllers;

use app\models\Joke;
use yii\web\Controller;

final class JokeController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ],
        ];
    }

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
