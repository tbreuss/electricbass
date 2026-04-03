<?php

namespace app\features\blog;

use app\components\Redirect;
use app\features\blog\models\Blog;
use app\helpers\Url;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $provider = Blog::getActiveDataProvider();
        $latest = Blog::findLatest(5);
        $popular = Blog::findPopular(5);
        return $this->render('@app/features/blog/views/index', [
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
     * @throws GoneHttpException
     */
    public function actionView($id): string
    {
        $blog = Blog::findOneOrNull('/blog/' . $id);

        if (is_null($blog)) {
            throw new GoneHttpException();
        }

        $similars = Blog::findSimilars($blog->id, $blog->getTagsAsArray(), 10);

        $redirect = Redirect::findOneByRequestUrl(\Yii::$app->request->url);
        Url::rememberReferrer(['blog/index'], 'blog');

        #$blog->increaseHits();

        return $this->render('@app/features/blog/views/view', [
            'blog' => $blog,
            'redirect' => $redirect,
            'similars' => $similars
        ]);
    }
}
