<?php

namespace app\controllers;

use app\helpers\Url;
use app\models\Album;
use app\models\Catalog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AlbumController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'app\filters\Canonical',
                'only' => ['index'],
                'filters' => ['sort', 'filter', 'artist']
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex($artist = '')
    {
        $filter = [];

        if (!empty($artist)) {
            $filter['artist'] = $artist;
        }

        $provider = Album::getActiveDataProvider($filter);
        $latest = Album::findLatest(5);
        $popular = Album::findPopular(5);

        return $this->render('index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort(),
            'filter' => $filter,
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    /**
     * @param int|string $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Album::findOneOrNull('/katalog/alben/' . $id);

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $similars = Album::findSimilars($model->id, $model->getTagsAsArray(), 10);

        Url::rememberReferrer(['album/index'], 'album');
        #$model->increaseHits();

        return $this->render('view', [
            'model' => $model,
            'similars' => $similars
        ]);
    }

    public function actionAll()
    {
        $entries = $this->makeAtoZ(Album::findAllAtoZ());
        $latest = Album::findLatest(5);
        $popular = Album::findPopular(5);
        return $this->render('all', [
            'entries' => $entries,
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    private function makeAtoZ($models)
    {
        $char = '';
        $structure = [];
        foreach ($models as $model) {
            $firstChar = strtoupper(substr($model->artist, 0, 1));
            if (is_numeric($firstChar)) {
                $firstChar = '0-9';
            }
            if ($char !== $firstChar) {
                $char = $firstChar;
            }
            $structure[$firstChar][] = [
                'artist' => $model->artist,
                'title' => $model->title,
                'url' => $model->url,
                'isNew' => $model->isNew()
            ];
        }

        $flat = [];
        foreach ($structure as $key => $value) {
            $flat[] = [
                'initial' => $key,
                'entries' => $value,
            ];
        }

        return $flat;
    }
}
