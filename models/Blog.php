<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Media;
use app\traits\SimilarModelsByTags;
use app\traits\WithChanges;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

/**
 * @property int $id
 * @property string $text
 * @property string $title
 * @property string $countryCode
 * @property string $language
 * @property string $abstract
 * @property int $ratings
 * @property float $ratingAvg
 * @property int $hits
 * @property string $created
 * @property string $modified
 * @property string $urlSegment
 * @property string $pageTitle
 * @property string $metaDescription
 * @property int $comments
 * @property int $hideFoto
 * @property string $url
 */
final class Blog extends ActiveRecord
{
    use SimilarModelsByTags;
    use WithChanges;

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
                    'label' => 'Blogpost-Titel',
                ],
                'publication' => [
                    'asc' => ['publication' => SORT_ASC],
                    'desc' => ['publication' => SORT_DESC],
                    'default' => SORT_DESC,
                    'label' => 'Veröffentlichung',
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

        $query = Blog::find()
            ->select('id, title, abstract, url, created, modified')
            ->where(['deleted' => NULL, 'movedTo' => null])
            ->orderBy($sort->orders);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
            ],
            'sort' => $sort,
        ]);
    }

    /**
     * @param int|string $id
     */
    public static function findOneOrNull($id): ?Blog
    {
        $blog = Blog::find()->where(['deleted' => NULL, 'url' => $id])->one();
        if ($blog) {
            return $blog;
        }
        $blog = Blog::find()->where(['deleted' => NULL, 'id' => $id])->one();
        if ($blog) {
            return $blog;
        }
        return null;
    }

    /**
     * @return Blog[]
     */
    public static function findLatest(int $limit, int $id = 0): array
    {
        return self::find()
            ->where([
                'deleted' => NULL,
                'movedTo' => NULL
            ])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @return Blog[]
     */
    public static function findPopular(int $limit, int $id = 0): array
    {
        return self::find()
            ->where([
                'deleted' => NULL,
                'movedTo' => NULL
            ])
            ->andWhere(['>', 'ratingAvg', 0])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('ratingAvg DESC, ratings DESC, modified DESC')
            ->limit($limit)
            ->all();
    }

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
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_BLOG_IDS', $ids);
        }
    }

}
