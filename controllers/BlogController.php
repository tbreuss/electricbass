<?php

namespace app\controllers;

use app\helpers\Url;
use app\models\Blog;
use app\models\Redirect;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class BlogController extends Controller
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
                'filters' => ['sort', 'tag', 'filter']
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Blog::getActiveDataProvider();
        $latest = Blog::findLatest(5);
        $popular = Blog::findPopular(5);
        return $this->render('index', [
            'dataProvider' => $provider,
            'blogs' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort(),
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    /**
     * @param int|string $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $blog = Blog::findOneOrNull('/blog/' . $id);

        if (is_null($blog)) {
            throw new NotFoundHttpException();
        }

        $similars = Blog::findSimilars($blog->id, $blog->getTagsAsArray(), 10);

        $redirect = Redirect::findOneByRequestUrl(\Yii::$app->request->url);
        Url::rememberReferrer(['blog/index'], 'blog');

        #$blog->increaseHits();

        return $this->render('view', [
            'blog' => $blog,
            'redirect' => $redirect,
            'similars' => $similars
        ]);
    }
}
