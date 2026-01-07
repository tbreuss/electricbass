<?php

namespace app\controllers;

use app\entities\AtoZEntry;
use app\entities\AtoZGroupedEntries;
use app\helpers\Url;
use app\models\Website;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

final class WebsiteController extends Controller
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
     * @return string
     */
    public function actionIndex(): string
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
    public function actionView($id): string
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

    public function actionAll(): string
    {
        $groupedEntries = $this->makeAtoZ(Website::findAllAtoZ());
        $latest = Website::findLatest(5);
        $popular = Website::findPopular(5);
        return $this->render('all', [
            'groupedEntries' => $groupedEntries,
            'latest' => $latest,
            'popular' => $popular
        ]);
    }

    /**
     * @param Website[] $models
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
}
