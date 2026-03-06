<?php

namespace app\features\quote;

use app\features\quote\models\Quote;

final class Controller extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Quote::getActiveDataProvider();
        return $this->render('@app/features/quote/views/index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }
}
