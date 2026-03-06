<?php

namespace app\feature\sitemap;

use app\feature\search\models\Search;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

final class Controller extends \yii\web\Controller
{
    public function actionGoogle(): string
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');

        $query = Search::getQueryObject();
        return $this->renderPartial('@app/feature/sitemap/views/google', [
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
                Url::to(['/music-paper/index'], true),
                date(\DateTime::ATOM, strtotime('2020-12-06 09:30'))
            ],
            [
                Url::to(['/quote/index'], true),
                date(\DateTime::ATOM, strtotime('2020-12-06 09:30'))
            ],
        ];
    }
}
