<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Media;
use app\traits\SimilarModelsByTags;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\helpers\Url;

/**
 * @property int $id
 * @property string $title
 * @property string $platform
 * @property string $key
 * @property string $url
 */
final class Video extends ActiveRecord
{
    use SimilarModelsByTags;

    public static function getActiveDataProvider(): ActiveDataProvider
    {
        $sort = new Sort([
            'attributes' => [
                'rating' => [
                    'asc' => ['ratingAvg' => SORT_ASC, 'ratings' => SORT_DESC],
                    'desc' => ['ratingAvg' => SORT_DESC, 'ratings' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Bewertung',
                ],
                'title' => [
                    'asc' => ['title' => SORT_ASC],
                    'desc' => ['title' => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Video-Titel',
                ],
                'created' => [
                    'asc' => ['created' => SORT_ASC],
                    'desc' => ['created' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Erstellungsdatum',
                ],
                'modified' => [
                    'asc' => ['modified' => SORT_ASC],
                    'desc' => ['modified' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Änderungsdatum',
                ],
                'comments' => [
                    'asc' => ['comments' => SORT_ASC],
                    'desc' => ['comments' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Kommentare',
                ],
            ],
            'defaultOrder' => [
                'modified' => SORT_DESC,
            ]
        ]);

        $query = self::find()
            ->select('id, title, platform, key, abstract, url, created, modified')
            ->where(['deleted' => null])
            ->orderBy($sort->orders);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 24,
            ],
            'sort' => $sort,
        ]);
    }

    public static function findOneOrNull(string $eid): ?Video
    {
        $video = self::find()->where(['deleted' => null, 'url' => $eid])->one();
        if ($video) {
            return $video;
        }
        $video = self::find()->where(['deleted' => null, 'eid' => $eid])->one();
        if ($video) {
            return $video;
        }
        return null;
    }

    /**
     * @return Video[]
     */
    public static function findLatest(int $id): array
    {
        return self::find()
            ->select('url, platform, key, title, abstract, tags, modified')
            ->where(['deleted' => null])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit(9)
            ->all();
    }

    /**
     * @param string $alias
     * @return string
     */
    public function getDefaultImage(string $alias = ''): string
    {
        if (!empty($alias)) {
            $alias = rtrim($alias, '/') . '/';
        }

        $image = '';
        $images = Media::getBlogImages($this->id);
        if (!empty($images)) {
            $image = $alias . $images[0];
        }

        return $image;
    }

    public function hasDefaultImage(): bool
    {
        $relpath = $this->getDefaultImage();
        return !empty($relpath);
    }

    public function increaseHits(): void
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_BLOG_IDS', []);
        if (!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_BLOG_IDS', $ids);
        }
    }
}
