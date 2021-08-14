<?php

namespace app\controllers;

use yii\web\Controller;

class PageController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class'=>'yii\web\ViewAction',
                'viewPrefix' => false
            ]
        ];
    }
}
