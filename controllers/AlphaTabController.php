<?php

namespace app\controllers;

use app\components\AlphaTabApi;
use app\models\AlphaDrums;
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
           'alphaTab' => AlphaTabApi::fromModels(
               tab: $model,
               drums: $model->drums,
               debug: Yii::$app->request->getQueryParam('debug') !== null,
           ),
        ]);
    }

    public function actionEditor(): string
    {
        $this->layout = 'empty';
        $notation = \Yii::$app->request->getBodyParam('content', '\title Test');
        $instrument = \Yii::$app->request->getBodyParam('instrument', 'NONE');
        $optionGroup = \Yii::$app->request->getBodyParam('optionGroup', 'NONE');
        $isDebug = \Yii::$app->request->getQueryParam('debug') !== null;
        return $this->render('editor', [
            'alphaTab' => new AlphaTabApi(
                alphaTex: $notation,
                optionsGroup: $optionGroup,
                instrument: $instrument,
                debug: $isDebug,
            ),
            'instrument' => $instrument,
            'optionGroup' => $optionGroup,
            'notation' => $notation,
        ]);
    }
}
