<?php

namespace app\features\joke;

use app\features\joke\models\Joke;

final class Controller extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Joke::getActiveDataProvider();
        return $this->render('@app/features/joke/views/index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }
}
