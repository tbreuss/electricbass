<?php

namespace app\feature\fingering;

use app\feature\fingering\models\Fingering;
use Yii;
use yii\helpers\Json;
use yii\web\GoneHttpException;
use yii\web\MethodNotAllowedHttpException;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(string $category = ''): string
    {
        $models = [];

        if ($category !== '') {
            $models = Fingering::findAllByCategory($category);
        }

        return $this->render('@app/feature/fingering/views/index', [
            'category' => $category,
            'models' => $models,
        ]);
    }

    /**
     * @throws GoneHttpException
     */
    public function actionView(string $id): string
    {
        $model = Fingering::findOneOrNull('/tools/fingersaetze/' . $id);

        if (is_null($model) || count(\Yii::$app->request->getQueryParams()) > 1) {
            throw new GoneHttpException();
        }

        $model->increaseHits();
        $modelsPerCategory = Fingering::findAllByCategory($model->category);

        return $this->render('@app/feature/fingering/views/view', [
            'model' => $model,
            'modelsPerCategory' => $modelsPerCategory,
            'root' => Yii::$app->request->getBodyParam('root', $model->root),
            'strings' => Yii::$app->request->getBodyParam('strings', $model->strings),
            'expand' => Yii::$app->request->getBodyParam('expand', '0'),
        ]);
    }

    public function actionTableOfContents(): string
    {
        if (!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        $body = Json::decode(Yii::$app->request->getRawBody());
        $models = Fingering::findAllByCategory($body['category']);

        return $this->renderPartial('@app/feature/fingering/views/category', [
            'models' => $models,
        ]);
    }
}
