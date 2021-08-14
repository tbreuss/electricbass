<?php

namespace app\controllers;

use app\models\Lesson;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class LessonController extends Controller
{
    public function behaviors()
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
    public function actionIndex($path = '')
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
     * @param Lesson $model
     * @return array
     */
    protected function getBreadcrumbs(Lesson $model)
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
