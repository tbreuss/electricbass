<?php

namespace app\features\joke;

use app\features\joke\models\Joke;
use Yii;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        if (count(Yii::$app->request->getQueryParams()) > 0) {
            throw new GoneHttpException();
        }

        $defaults = [
            'page' => '1',
            'sort' => 'joke',
        ];

        $page = (int)Yii::$app->request->getBodyParam('page', $defaults['page']);
        $sort = Yii::$app->request->getBodyParam('sort', $defaults['sort']);

        $provider = Joke::getActiveDataProvider(page: $page, sort: $sort);

        return $this->render('@app/features/joke/views/index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort(),
            'urlFragments' => [
                'applied' => ['page' => $page, 'sort' => $sort],
                'defaults' => $defaults,
            ],
        ]);
    }
}
