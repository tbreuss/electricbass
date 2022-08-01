<?php

namespace app\controllers;

use app\models\Quote;
use yii\web\Controller;

final class QuoteController extends Controller
{
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ]
        ];
    }
    
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Quote::getActiveDataProvider();
        return $this->render('index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort()
        ]);
    }
}
