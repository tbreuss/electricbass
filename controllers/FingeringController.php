<?php

namespace app\controllers;

use app\models\Fingering;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\JsonParser;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

final class FingeringController extends Controller
{
    public function actionIndex(string $category = ''): string
    {
        $models = [];

        if ($category !== '') {
            $models = Fingering::findAllByCategory($category);
        }

        return $this->render('index', [
            'category' => $category,
            'models' => $models,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $id): string
    {
        $model = Fingering::findOneOrNull('/tools/fingersaetze/' . $id);

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $modelsPerCategory = Fingering::findAllByCategory($model->category);
        $model->increaseHits();

        return $this->render('view', [
            'model' => $model,
            'modelsPerCategory' => $modelsPerCategory,
        ]);
    }

    public function actionTableOfContents(): string
    {
        if (!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        $body = Json::decode(Yii::$app->request->getRawBody());
        $models = Fingering::findAllByCategory($body['category']);

        return $this->renderPartial('category', [
            'models' => $models,
        ]);
    }
}
