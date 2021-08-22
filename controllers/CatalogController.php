<?php

namespace app\controllers;

use Amazon\ProductAdvertisingAPI\v1\ApiException;
use app\components\AmazonProductDetail;
use app\helpers\Url;
use app\models\Catalog;
use Throwable;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => 'app\filters\Canonical',
                'only' => ['index'],
                'filters' => ['sort', 'filter', 'series', 'publisher', 'autor'],
                'params' => ['category']
            ]
        ];
    }

    public function actionAll(string $category)
    {
        $entries = $this->makeAtoZ(Catalog::findAllAtoZ($category));
        $latest = Catalog::findLatest($category, 10);
        $popular = Catalog::findPopular($category, 10);

        Yii::$app->view->params['pageTitle'] = $this->getPageTitleForAtoZ($category);
        Yii::$app->view->params['metaDescription'] = $this->getMetaDescriptionForAtoZ($category);

        return $this->render('all', [
            'entries' => $entries,
            'title' => $this->getListTitleForAtoZ($category),
            'category' => $category,
            'sidebarTitle' => $this->getSidebarTitle($category),
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

    /**
     * @param string $category
     * @return string
     */
    public function actionIndex($category, $autor = '', $publisher = '', $series = '')
    {
        $filter = [];

        if (!empty($autor)) {
            $filter['autor'] = $autor;
        }
        if (!empty($publisher)) {
            $filter['publisher'] = $publisher;
        }
        if (!empty($series)) {
            $filter['series'] = $series;
        }

        $provider = Catalog::getActiveDataProvider($category, $filter);
        $latest = Catalog::findLatest($category, 10);
        $popular = Catalog::findPopular($category, 10);

        return $this->render('index', [
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort(),
            'title' => $this->getListTitle($category),
            'sidebarTitle' => $this->getSidebarTitle($category),
            'pageTitle' => $this->getPageTitle($category, $provider->getPagination()),
            'metaDescription' => $this->getMetaDescription($category,$provider->getPagination()),
            'filter' => $filter,
            'category' => $category,
            'context' => $this->getContext($category),
            'latest' => $latest,
            'popular' => $popular
        ]);
    }


    /**
     * @throws \yii\db\Exception
     * @throws NotFoundHttpException
     */
    public function actionView($id, string $category): string
    {
        $model = Catalog::findOneOrNull('/katalog/' . $category . '/' . $id, $category);

        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $similars = Catalog::findSimilars($model->id, $model->getTagsAsArray(), 10);

        Url::rememberReferrer(['catalog/index', 'category' => $category], 'catalog' . $category);

        #$model->increaseHits();

        $amazonProductDetail = null; // $this->getAmazonDetail($model->asin);

        return $this->render('view', [
            'title' => $this->getListTitle($category),
            'model' => $model,
            'amazonProductDetail' => $amazonProductDetail,
            'similars' => $similars,
            'category' => $category
        ]);
    }

    private function getAmazonDetail($asin)
    {
        try {
            return AmazonProductDetail::createByAsin($asin);
        } catch (ApiException $e) {
        } catch (Throwable $e) {
        }
        return null;
    }

    public function actionOverview()
    {
        $this->layout = 'onecol';
        return $this->render('overview');
    }

    protected function getListTitle($category)
    {
        $categories = [
            'lehrbuecher' => 'Lehrbücher mit CDs für E-Bass',
            'buecher' => 'Allgemeine Bücher für Bassisten',
            'dvds' => 'Lehrbücher mit DVDs für E-Bass'
        ];
        return $categories[$category] ?? 'Alle';
    }

    protected function getListTitleForAtoZ($category)
    {
        $categories = [
            'lehrbuecher' => 'Lehrbücher für E-Bass von A-Z',
            'buecher' => 'Bücher für Bassisten von A-Z',
            'dvds' => 'Lehrvideos für E-Bass von A-Z'
        ];
        return $categories[$category] ?? 'Alle';
    }

    protected function getPageTitleForAtoZ(string $category): string
    {
        $categories = [
            'lehrbuecher' => 'Lehrbücher mit CDs zum E-Bass spielen lernen',
            'buecher' => 'Allgemeine Bücher für Bassisten zum Thema Bass',
            'dvds' => 'Lehrbücher und Lehrvideos mit DVDs zum E-Bass lernen',
        ];
        return $categories[$category] ?? 'Alle';
    }

    protected function getMetaDescriptionForAtoZ(string $category): string
    {
        $categories = [
            'lehrbuecher' => 'Du willst E-Bass spielen lernen? Hier findest du Lehrbücher mit CDs zum selber lernen. Mit Noten, TABs und Playalongs',
            'buecher' => 'Allgemeine Bücher zum Thema Bass für Bassisten, E-Bassisten und Musikinteressierte',
            'dvds' => 'Du willst E-Bass spielen lernen? Hier findest du Lehrbücher/-videos mit DVDs zum selber lernen. Mit Videos, Noten, TABs und Playalongs',
        ];
        return $categories[$category] ?? 'Alle';
    }

    /**
     * @param string $category
     * @param Pagination $pagination
     * @return string Maximal 60 Zeichen
     */
    protected function getPageTitle(string $category, Pagination $pagination): string
    {
        $page = $pagination->getPage() + 1;
        $pageCount = $pagination->getPageCount();
        $categories = [
            'lehrbuecher' => sprintf('Lehrbücher inkl. CDs zum E-Bass spielen lernen (%d/%d)', $page, $pageCount),
            'buecher' => sprintf('Allgemeine Bücher für Bassisten zum Thema Bass (%d/%d)', $page, $pageCount),
            'dvds' => sprintf('Lehrbücher und -videos inkl. DVDs zum E-Bass lernen (%d/%d)', $page, $pageCount),
        ];

        return isset($categories[$category]) ? $categories[$category] : 'Alle';
    }

    /**
     * @param string $category
     * @param Pagination $pagination
     * @return string Maximal 155 Zeichen
     */
    protected function getMetaDescription(string $category, Pagination $pagination): string
    {
        $categories = [
            'lehrbuecher' => sprintf('Du willst E-Bass spielen lernen? Hier findest du Lehrbücher mit CDs zum selber lernen. Mit Noten, TABs und Playalongs - Seite %d von %d', $pagination->page+1, $pagination->pageCount),
            'buecher' => sprintf('Allgemeine Bücher zum Thema Bass für Bassisten, E-Bassisten und Musikinteressierte - Seite %d von %d', $pagination->page+1, $pagination->pageCount),
            'dvds' => sprintf('Du willst E-Bass spielen lernen? Hier findest du Lehrbücher/-videos mit DVDs zum selber lernen. Mit Videos, Noten, TABs und Playalongs - Seite %d von %d', $pagination->page+1, $pagination->pageCount)
        ];
        return isset($categories[$category]) ? $categories[$category] : 'Alle';
    }

    protected function getSidebarTitle($category)
    {
        $categories = [
            'lehrbuecher' => 'Lehrbücher',
            'buecher' => 'Bassbücher',
            'dvds' => 'Lehrbücher'
        ];
        return $categories[$category] ?? 'Alle';
    }

    public function getContext($category)
    {
        switch($category) {
            case 'buecher':
                return 'buch';
            case 'lehrbuecher':
                return 'lehrbuch';
            case 'dvds':
                return 'dvd';
        }
    }

}
