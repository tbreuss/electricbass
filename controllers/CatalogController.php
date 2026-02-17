<?php

namespace app\controllers;

use Amazon\ProductAdvertisingAPI\v1\ApiException;
use app\components\AmazonProductDetail;
use app\entities\AtoZEntry;
use app\entities\AtoZGroupedEntries;
use app\helpers\Url;
use app\models\Catalog;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class CatalogController extends Controller
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

    public function actionAll(string $category): string
    {
        $groupedEntries = $this->makeAtoZ(Catalog::findAllAtoZ($category));
        $latest = Catalog::findLatest($category, 10);
        $popular = Catalog::findPopular($category, 10);

        Yii::$app->view->params['pageTitle'] = $this->getPageTitleForAtoZ($category);
        Yii::$app->view->params['metaDescription'] = $this->getMetaDescriptionForAtoZ($category);

        return $this->render('all', [
            'groupedEntries' => $groupedEntries,
            'title' => $this->getListTitleForAtoZ($category),
            'category' => $category,
            'sidebarTitle' => $this->getSidebarTitle($category),
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    /**
     * @param Catalog[] $models
     * @return AtoZGroupedEntries[]
     */
    private function makeAtoZ(array $models): array
    {
        $char = '';
        $entries = [];
        foreach ($models as $model) {
            $firstChar = strtoupper(substr($model->title, 0, 1));
            if (is_numeric($firstChar)) {
                $firstChar = '0-9';
            }
            if ($char !== $firstChar) {
                $char = $firstChar;
            }
            $entries[$firstChar][] = new AtoZEntry(
                $model->title,
                $model->url,
                $model->isNew()
            );
        }

        $groups = [];
        foreach ($entries as $key => $value) {
            $groups[] = new AtoZGroupedEntries($key, $value);
        }

        return $groups;
    }

    public function actionIndex(string $category, string $autor = '', string $publisher = '', string $series = ''): string
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

        if ($provider->getModels() === []) {
            throw new NotFoundHttpException();
        }

        $latest = Catalog::findLatest($category, 10);
        $popular = Catalog::findPopular($category, 10);

        return $this->render('index', [
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort(),
            'title' => $this->getListTitle($category),
            'sidebarTitle' => $this->getSidebarTitle($category),
            'pageTitle' => $this->getPageTitle($category, $provider),
            'metaDescription' => $this->getMetaDescription($category, $provider),
            'filter' => $filter,
            'category' => $category,
            'context' => $this->getContext($category),
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    /**
     * @param int|string $id
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

    private function getAmazonDetail(string $asin): ?AmazonProductDetail
    {
        try {
            return AmazonProductDetail::createByAsin($asin);
        } catch (ApiException $e) {
        } catch (Throwable $e) {
        }
        return null;
    }

    public function actionOverview(): string
    {
        $this->layout = 'onecol';
        return $this->render('overview');
    }

    protected function getListTitle(string $category): string
    {
        $categories = [
            'lehrbuecher' => 'E-Bass Lehrbücher mit CDs',
            'buecher' => 'Allgemeine Bücher zum Thema Bass',
            'dvds' => 'E-Bass Lehrbücher mit DVDs'
        ];
        return $categories[$category] ?? 'Alle';
    }

    protected function getListTitleForAtoZ(string $category): string
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

    protected function getPageTitle(string $category, ActiveDataProvider $provider): string
    {
        $pagination = $provider->getPagination();
        if (is_bool($pagination)) {
            return ''; // TODO return better text if no pagination available
        }

        $page = $pagination->getPage() + 1;
        $pageCount = $pagination->getPageCount();
        $categories = [
            'lehrbuecher' => sprintf('Lehrbücher inkl. CDs zum E-Bass spielen lernen (%d/%d)', $page, $pageCount),
            'buecher' => sprintf('Allgemeine Bücher für Bassisten zum Thema Bass (%d/%d)', $page, $pageCount),
            'dvds' => sprintf('Lehrbücher und -videos inkl. DVDs zum E-Bass lernen (%d/%d)', $page, $pageCount),
        ];

        return $categories[$category] ?? '';
    }

    protected function getMetaDescription(string $category, ActiveDataProvider $provider): string
    {
        $pagination = $provider->getPagination();
        if (is_bool($pagination)) {
            return ''; // TODO return better text if no pagination available
        }

        $categories = [
            'lehrbuecher' => sprintf('Du willst E-Bass spielen lernen? Hier findest du Lehrbücher mit CDs zum selber lernen. Mit Noten, TABs und Playalongs - Seite %d von %d', $pagination->page + 1, $pagination->pageCount),
            'buecher' => sprintf('Allgemeine Bücher zum Thema Bass für Bassisten, E-Bassisten und Musikinteressierte - Seite %d von %d', $pagination->page + 1, $pagination->pageCount),
            'dvds' => sprintf('Du willst E-Bass spielen lernen? Hier findest du Lehrbücher/-videos mit DVDs zum selber lernen. Mit Videos, Noten, TABs und Playalongs - Seite %d von %d', $pagination->page + 1, $pagination->pageCount)
        ];
        return $categories[$category] ?? '';
    }

    protected function getSidebarTitle(string $category): string
    {
        $categories = [
            'lehrbuecher' => 'Lehrbücher',
            'buecher' => 'Bassbücher',
            'dvds' => 'Lehrbücher'
        ];
        return $categories[$category] ?? 'Alle';
    }

    public function getContext(string $category): string
    {
        switch ($category) {
            case 'buecher':
                return 'buch';
            case 'lehrbuecher':
                return 'lehrbuch';
            case 'dvds':
                return 'dvd';
        }
        return '';
    }
}
