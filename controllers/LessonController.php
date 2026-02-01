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
                'class' => 'app\filters\RedirectFilter'
            ],
        ];
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex(string $path, ?string $preview = null): string
    {
        $model = Lesson::find()->where('url = :url', [':url' => '/' . $path])->one();

        if (is_null($model) || ($model->deleted === 1 && is_null($preview))) {
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
            'title' => $this->getTitle($model),
            'breadcrumbs' => $this->getBreadcrumbs($model),
            'similars' => $similars,
            'latest' => $latest
        ]);
    }

    /**
     * @phpstan-return array<int, array{"label": string, "url": string}|string>
     */
    protected function getBreadcrumbs(Lesson $model): array
    {
        static $breadcrumbs;
        if (!is_array($breadcrumbs)) {
            $parents = Lesson::findParents($model->url);
            foreach ($parents as $parent) {
                if ($parent->url === $model->url) {
                    continue;
                }
                $breadcrumbs[] = [
                    'label' => $parent->menuTitle,
                    'url' => $parent->url
                ];
            }
            $breadcrumbs[] = $model->menuTitle;
        }
        return $breadcrumbs;
    }

    protected function getTitle(Lesson $model): string
    {
        $segments = [];
        foreach ($this->getBreadcrumbs($model) as $breadcrumb) {
            if (is_string($breadcrumb)) {
                $segments[] = $breadcrumb;
            } elseif (isset($breadcrumb['label'])) {
                $segments[] = $breadcrumb['label'];
            }
        }
        return join(' | ', array_reverse($segments));
    }
}
