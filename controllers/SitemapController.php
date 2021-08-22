<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use app\models\Search;

final class SitemapController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionGoogle(): string
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');

        $query = Search::getQueryObject();
        return $this->renderPartial('google', [
            'query' => $query,
            'staticPages' => $this->getStaticPages()
        ]);
    }

    /**
     * @phpstan-return array<int, array{string, string}>
     */
    private function getStaticPages(): array
    {
        return [
            [
                Url::to(['/tool/musicpaper'], true),
                date(\DateTime::ATOM, strtotime('2020-12-06 09:30'))
            ],
            [
                Url::to(['/quote/index'], true),
                date(\DateTime::ATOM, strtotime('2020-12-06 09:30'))
            ],
        ];
    }
}
