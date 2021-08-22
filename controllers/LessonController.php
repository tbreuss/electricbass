<?php

namespace app\controllers;

use app\models\Lesson;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class LessonController extends Controller
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
                'filters' => ['sort', 'filter']
            ]
        ];
    }

    /**
     * @param string $path
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($path = ''): string
    {
        $url = rtrim('/lektionen/' . $path, '/');
        $model = Lesson::find()->where('deleted = 0 AND url = :url', [':url' => $url])->one();

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $tags = empty($model->tags) ? [] : explode(',', $model->tags);
        $similars = Lesson::findSimilars($model->id, $tags, 10);
        $latest = [];
        if (empty($similars)) {
            $latest = Lesson::findLatest($model->id, 10);
        }

        #$model->increaseHits();

        return $this->render('index', [
            'model' => $model,
            'breadcrumbs' => $this->getBreadcrumbs($model),
            'similars' => $similars,
            'latest' => $latest
        ]);
    }

    /**
     * @phpstan-return array<int, array{"label": string, "url": string}>
     */
    protected function getBreadcrumbs(Lesson $model): array
    {
        $parents = Lesson::findParents($model->url);
        $breadcrumbs = [];
        foreach ($parents as $parent) {
            if ($parent->url === $model->url) {
                continue;
            }
            $breadcrumbs[] = [
                'label' => $parent->menuTitle,
                'url' => $parent->url
            ];
        }
        return $breadcrumbs;
    }

}
