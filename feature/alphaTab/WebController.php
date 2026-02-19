<?php

namespace app\feature\alphaTab;

use app\feature\alphaTab\components\AlphaTabApi;
use app\feature\alphaTab\models\AlphaTab;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

final class WebController extends Controller
{
    public function actionView(string $uid): string
    {
        $model = (new AlphaTab)->findByUid($uid);

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $this->layout = 'empty';
        return $this->render('@app/feature/alphaTab/views/view', [
           'alphaTab' => AlphaTabApi::fromModels(
               tab: $model,
               drums: $model->drums,
               debug: \Yii::$app->request->getQueryParam('debug') !== null,
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
        return $this->render('@app/feature/alphaTab/views/editor', [
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

    public function actionDebug(string $key = ''): string
    {
        if ($key !== $_ENV['DEBUG_KEY']) {
            throw new UnauthorizedHttpException();
        }
        $models = AlphaTab::find()->orderBy('uid')->all();
        return $this->render('@app/feature/alphaTab/views/debug', [
            'models' => $models,
        ]);
    }
}
