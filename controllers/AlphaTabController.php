<?php

namespace app\controllers;

use yii\web\Controller;

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

    public function actionEditor(): string
    {
        $this->layout = 'empty';
        $content = \Yii::$app->request->getBodyParam('content', '\title Test');
        return $this->render('editor', [
            'content' => $content,
        ]);
    }
}
