<?php

namespace app\feature\joke;

use app\feature\joke\models\Joke;

final class Controller extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Joke::getActiveDataProvider();
        return $this->render('@app/feature/joke/views/index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }
}
