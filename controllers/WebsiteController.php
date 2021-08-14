<?php

namespace app\controllers;

use app\helpers\Url;
use app\models\Website;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class WebsiteController extends Controller
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
     * @return string
     */
    public function actionIndex()
    {
        $provider = Website::getActiveDataProvider();
        $latest = Website::findLatest(5);
        $popular = Website::findPopular(5);
        return $this->render('index', [
            'dataProvider' => $provider,
            'websites' => $provider->getModels(),
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
    public function actionView($id)
    {
        $website = Website::findOneOrNull('/websites/' . $id);

        if (is_null($website)) {
            throw new NotFoundHttpException();
        }

        $similars = Website::findSimilars($website->id, $website->getTagsAsArray(), 10);

        Url::rememberReferrer(['website/index'], 'website');
        #$website->increaseHits();

        return $this->render('view', [
            'website' => $website,
            'similars' => $similars
        ]);
    }

    public function actionAll()
    {
        $entries = $this->makeAtoZ(Website::findAllAtoZ());
        $latest = Website::findLatest(5);
        $popular = Website::findPopular(5);
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
            $firstChar = strtoupper(substr($model->title, 0, 1));
            if (is_numeric($firstChar)) {
                $firstChar = '0-9';
            }
            if ($char !== $firstChar) {
                $char = $firstChar;
            }
            $structure[$firstChar][] = [
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
