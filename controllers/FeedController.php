<?php

namespace app\controllers;

use app\models\Search;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Response;

final class FeedController extends Controller
{
    /**
     * @phpstan-return array<array>
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => 'app\filters\RedirectFilter'
            ]
        ];
    }

    public function actionRss(): string
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');

        $query = (new Query())
            ->select([
                'tableName',
                'tableId',
                'id',
                'url',
                'title',
                'abstract',
                'category',
                'modified'
            ])
            ->from('search')
            ->orderBy('modified DESC')
            ->limit(500);

        return $this->renderPartial('rss', [
            'query' => $query
        ]);
    }
}
