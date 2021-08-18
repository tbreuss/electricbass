<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Media;
use app\traits\SimilarModelsByTags;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

/**
 * @property int $id
 * @property string $website
 * @property string $modified
 */
class Website extends ActiveRecord
{
    use SimilarModelsByTags;

    /**
     * @return ActiveDataProvider
     */
    public static function getActiveDataProvider()
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
                    'label' => 'Websitetitel',
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
            ->select('id, title, abstract, website, url')
            ->where(['deleted' => NULL])
            ->orderBy($sort->orders);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 24,
            ],
            'sort' => $sort,
        ]);

        return $provider;
    }

    /**
     * @param int|string $id
     * @return null|Website
     */
    public static function findOneOrNull($id)
    {
        $website = self::find()->where(['deleted' => NULL, 'url' => $id])->one();
        if ($website) {
            return $website;
        }
        $website = self::find()->where(['deleted' => NULL, 'id' => $id])->one();
        if ($website) {
            return $website;
        }
        return null;
    }

    /**
     * @param int $limit
     * @param int $id
     * @return array
     */
    public static function findLatest(int $limit, int $id = 0): array
    {
        return static::find()
            ->where(['deleted' => null])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param int $limit
     * @param int $id
     * @return array
     */
    public static function findPopular(int $limit, int $id = 0): array
    {
        return static::find()
            ->where(['deleted' => null])
            ->andWhere(['>', 'ratingAvg', 0])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('ratingAvg DESC, ratings DESC, modified DESC')
            ->limit($limit)
            ->all();
    }

    public static function findAllAtoZ(): array
    {
        return static::find()
            ->select('id, title, website, url, modified')
            ->where([
                'deleted' => null
            ])
            ->orderBy(
                'title ASC'
            )
            ->all();
    }

    /**
     * @return string[]
     * @throws \yii\db\Exception
     */
    public static function findUrls(): array
    {
        $sql = <<<SQL
                SELECT website
            FROM website
            WHERE deleted IS NULL
        SQL;
        return Yii::$app->db->createCommand($sql)->queryColumn();
    }

    /**
     * @return array
     */
    public function getProductInfos()
    {
        $infos = [];
        if (!empty($this->publisher)) {
            $infos[] = [
                'key' => 'publisher',
                'label' => 'Verlag',
                'value' => $this->publisher
            ];
        }
        if (!empty($this->autor)) {
            $infos[] = [
                'key' => 'autor',
                'label' => 'Autor',
                'value' => $this->autor
            ];
        }
        if (!empty($this->series)) {
            $infos[] = [
                'key' => 'series',
                'label' => 'Serie',
                'value' => $this->series
            ];
        }
        if (!empty($this->info)) {
            $infos[] = [
                'key' => 'info',
                'label' => 'Info',
                'value' => nl2br($this->info)
            ];
        }
        if (!empty($this->asin)) {
            $infos[] = [
                'key' => 'asin',
                'label' => 'ASIN',
                'value' => $this->asin
            ];
        }
        if (!empty($this->isbn)) {
            $infos[] = [
                'key' => 'isbn',
                'label' => 'ISBN',
                'value' => $this->isbn
            ];
        }
        if (!empty($this->ean)) {
            $infos[] = [
                'key' => 'ean',
                'label' => 'EAN',
                'value' => $this->ean
            ];
        }
        if (!empty($this->publication)) {
            $infos[] = [
                'key' => 'publication',
                'label' => 'Publiziert',
                'value' => Yii::$app->formatter->asDate($this->publication, 'long')
            ];
        }
        if (!empty($this->language)) {
            $infos[] = [
                'key' => 'language',
                'label' => 'Sprache',
                'value' => $this->language
            ];
        }
        return $infos;
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
        $relpath = Media::getWebsiteImage($this->website);
        if (!empty($relpath)) {
            $image = $alias . $relpath;
        }

        return $image;
    }

    /**
     * @return bool
     */
    public function hasDefaultImage(): bool
    {
        $relpath = $this->getDefaultImage();
        return !empty($relpath);
    }

    public function increaseHits()
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_WEBSITE_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_WEBSITE_IDS', $ids);
        }
    }

    public function isNew()
    {
        $now = time(); // or your date as well
        $date = strtotime($this->modified);
        $days = round(($now - $date) / (60 * 60 * 24));
        return $days < 60;
    }
}
