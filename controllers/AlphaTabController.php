<?php

namespace app\controllers;

use app\models\AlphaTab;
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
           'model' => $model,
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
