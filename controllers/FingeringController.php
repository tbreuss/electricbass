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
                'class' => 'app\filters\RedirectFilter'
            ],
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
            ->orderBy([new \yii\db\Expression('FIELD (category, "intervall", "akkord", "tonleiter"), title ASC')])
            ->all();

        $modelsGroupedByCategory = array_reduce($models, function (array $accumulator, $model) {
            $noteCount = count($model->getNotesInStandardFormat());
            $accumulator[$model->category][] = array_merge(
                $model->toArray(),
                [
                    'notesStandardFormat' => $model->getNotesInStandardFormat(),
                    'noteCount' => $model->category == 'tonleiter' ? $noteCount - 1 : $noteCount
                ],
            );
            return $accumulator;
        }, []);

        foreach ($modelsGroupedByCategory as $category => $models) {
            usort($models, function ($a, $b) {
                return $a['noteCount'] <=> $b['noteCount'];
            });
            $modelsGroupedByCategory[$category] = array_reduce($models, function (array $accumulator, $model) {
                $accumulator[$model['noteCount']][] = $model;
                return $accumulator;
            }, []);
        }

        return $this->render('index', [
            'models' => $modelsGroupedByCategory
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
