<?php

namespace app\controllers;

use app\models\Fingering;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class FingeringController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\Canonical',
                'only' => ['index'],
                'filters' => ['tag']
            ],
            [
                'class' => 'app\filters\Canonical',
                'only' => ['view'],
                'filters' => ['root', 'position', 'strings'],
                'params' => ['id']
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $models = Fingering::find()
            ->where('deleted=0')
            ->orderBy('category ASC, title ASC')
            ->all();

        $this->layout = 'onecol';
        return $this->render('index', [
            'models' => $models
        ]);
    }

    /**
     * @param int|string $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $model = Fingering::findOneOrNull('/tools/fingersaetze/' . $id);

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $similars = Fingering::findSimilars($model->id, $model->getTagsAsArray(), 10);

        #$model->increaseHits();

        return $this->render('view', [
            'model' => $model,
            'similars' => $similars
        ]);
    }

}
