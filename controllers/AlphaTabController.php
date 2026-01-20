<?php

namespace app\controllers;

use app\components\AlphaTabApi;
use app\models\AlphaTab;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class AlphaTabController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ]
        ];
    }

    public function actionView(string $uid): string
    {
        $model = (new AlphaTab)->findByUid($uid);

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $this->layout = 'empty';
        return $this->render('view', [
           'alphaTab' => new AlphaTabApi(
               notation: $model->notation,
               options: $model->options,
               instrument: $model->instrument,
               debug: Yii::$app->request->getQueryParam('debug') !== null,
           ),
        ]);
    }

    public function actionEditor(): string
    {
        $this->layout = 'empty';
        $content = \Yii::$app->request->getBodyParam('content', '\title Test');
        return $this->render('editor', [
            'content' => $content,
        ]);
    }
}
