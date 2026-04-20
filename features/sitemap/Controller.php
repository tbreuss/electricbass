<?php

namespace app\features\sitemap;

use app\features\search\models\Search;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

final class Controller extends \yii\web\Controller
{
    public function actionImages(): string
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');
        return $this->renderPartial('@app/features/sitemap/views/images', [
            'hostInfo' => $_ENV['HOST_INFO'],
            'urls' => $this->getImages(),
        ]);
    }

    public function actionPages(): string
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/xml');

        $query = Search::getQueryObject();
        return $this->renderPartial('@app/features/sitemap/views/pages', [
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

    private function getImages(): array
    {
        $path = (string)Yii::getAlias('@webroot/media/scores');
        $url = (string)Yii::getAlias('@web/media/scores');

        $images = [];
        foreach ($this->fetchImages() as $pageUrl => $items) {
            foreach ($items as $item) {
                $imgPath = $path . '/' . str_replace('/', '-x', $item['uid']) . '.webp';
                if (file_exists($imgPath)) {
                    $images[$pageUrl][] = [
                        'loc' => $url . '/' . str_replace('/', '-x', $item['uid']) . '.webp',
                        'title' => $item['title'],
                    ];
                }
            }
        }

        return $images;
    }

    private function fetchImages(): array
    {
        $sql = '
            SELECT lesson.url, alpha_tab.uid, alpha_tab.title
            FROM alpha_tab
            INNER JOIN lesson ON lesson.text LIKE CONCAT(\'%"\', alpha_tab.uid, \'"%\') AND lesson.deleted = 0        
        ';
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP);
    }
}
