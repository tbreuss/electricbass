<?php

namespace app\features\quote;

use app\features\quote\models\Quote;
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

        $provider = Quote::getActiveDataProvider();
        return $this->render('@app/features/quote/views/index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }
}
