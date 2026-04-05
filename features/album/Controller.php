<?php

namespace app\features\album;

use app\components\entities\AtoZEntry;
use app\components\entities\AtoZGroupedEntries;
use app\features\album\models\Album;
use app\helpers\Url;
use Yii;
use yii\web\GoneHttpException;

final class Controller extends \yii\web\Controller
{
    public function actionIndex(): string
    {
        if (count(Yii::$app->request->getQueryParams()) > 0) {
            throw new GoneHttpException();
        }

        $defaults = [
            'page' => '1',
            'sort' => '-modified',
            'artist' => '',
        ];

        $page = (int)Yii::$app->request->getBodyParam('page', $defaults['page']);
        $sort = Yii::$app->request->getBodyParam('sort', $defaults['sort']);
        $artist = Yii::$app->request->getBodyParam('artist', $defaults['artist']);

        $filter = [];
        if (!empty($artist)) {
            $filter['artist'] = $artist;
        }

        $provider = Album::getActiveDataProvider(
            page: $page,
            sort: $sort,
            additionalWhere: $filter,
        );

        $latest = Album::findLatest(5);
        $popular = Album::findPopular(5);

        return $this->render('@app/features/album/views/index', [
            'dataProvider' => $provider,
            'models' => $provider->getModels(),
            'pagination' => $provider->getPagination(),
            'sort' => $provider->getSort(),
            'filter' => $filter,
            'latest' => $latest,
            'popular' => $popular,
            'currentPage' => $page,
            'currentSort' => $sort,
            'urlFragments' => [
                'applied' => ['page' => $page, 'sort' => $sort, 'artist' => $artist],
                'defaults' => $defaults,
            ],
        ]);
    }

    /**
     * @param int|string $id
     * @throws GoneHttpException
     */
    public function actionView($id): string
    {
        $model = Album::findOneOrNull('/katalog/alben/' . $id);

        if (is_null($model)) {
            throw new GoneHttpException();
        }

        $similars = Album::findSimilars($model->id, $model->getTagsAsArray(), 10);

        Url::rememberReferrer(['album/index'], 'album');
        #$model->increaseHits();

        return $this->render('@app/features/album/views/view', [
            'model' => $model,
            'similars' => $similars
        ]);
    }

    public function actionAll(): string
    {
        $groupedEntries = $this->makeAtoZ(Album::findAllAtoZ());
        $latest = Album::findLatest(5);
        $popular = Album::findPopular(5);
        return $this->render('@app/features/album/views/all', [
            'groupedEntries' => $groupedEntries,
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    /**
     * @param Album[] $models
     * @return AtoZGroupedEntries[]
     */
    private function makeAtoZ(array $models): array
    {
        $char = '';
        $entries = [];
        foreach ($models as $model) {
            $firstChar = strtoupper(substr($model->artist, 0, 1));
            if (is_numeric($firstChar)) {
                $firstChar = '0-9';
            }
            if ($char !== $firstChar) {
                $char = $firstChar;
            }
            $entries[$firstChar][] = new AtoZEntry(
                $model->artist . ' - ' . $model->title,
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
}
