<?php

namespace app\controllers;

use yii\web\Controller;

final class PageController extends Controller
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
    
    /**
     * @inheritdoc
     * @phpstan-return array<string, array<string, mixed>>
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => 'yii\web\ViewAction',
                'viewPrefix' => false
            ]
        ];
    }
}
