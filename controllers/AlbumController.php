<?php

namespace app\controllers;

use app\entities\AtoZEntry;
use app\entities\AtoZGroupedEntries;
use app\helpers\Url;
use app\models\Album;
use app\models\Catalog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class AlbumController extends Controller
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
            [
                'class' => 'app\filters\Canonical',
                'only' => ['index'],
                'filters' => ['sort', 'filter', 'artist']
            ]
        ];
    }

    public function actionIndex(string $artist = ''): string
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
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
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

    public function actionAll(): string
    {
        $groupedEntries = $this->makeAtoZ(Album::findAllAtoZ());
        $latest = Album::findLatest(5);
        $popular = Album::findPopular(5);
        return $this->render('all', [
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
