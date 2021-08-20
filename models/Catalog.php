<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Html;
use app\helpers\Media;
use app\traits\SimilarModelsByTags;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\helpers\Url;

/**
 * @property int $id
 * @property string $title
 * @property string $autor
 * @property string $productNumber
 * @property string $text
 * @property string $url
 * @property string $category
 * @property string $modified
 */
class Catalog extends ActiveRecord
{
    const TYPE_TEXTBOOK = 1;
    const TYPE_READINGBOOK = 2;
    const TYPE_DVD = 3;

    use SimilarModelsByTags;

    /**
     * @param int $category
     * @return ActiveDataProvider
     */
    public static function getActiveDataProvider($category, array $filter = [])
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
                    'label' => 'Titel',
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

        $where = array_merge(['deleted' => 0, 'category' => $category], $filter);

        $query = self::find()
            ->select('id, title, abstract, url, category')
            ->where($where)
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
     * @param int $category
     * @return null|Catalog
     */
    public static function findOneOrNull($id, $category)
    {
        $model = self::find()->where(['deleted' => 0, 'url' => $id, 'category' => $category])->one();
        if ($model) {
            return $model;
        }
        $model = self::find()->where(['deleted' => 0, 'id' => $id, 'category' => $category])->one();
        if ($model) {
            return $model;
        }
        return null;
    }

    /**
     * @param int $id
     * @param array $tags
     * @param int $limit
     * @return array
     * @throws \yii\db\Exception
     */
    public static function findSimilars(int $id, array $tags, int $limit = 10): array
    {
        $ids = static::findSimilarsIds($id, $tags);
        if (empty($ids)) {
            return [];
        }
        return self::find()
            ->where('deleted = 0')
            ->andWhere(['id' => $ids])
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $category
     * @param int $limit
     * @param int $id
     * @return array
     */
    public static function findLatest(string $category, int $limit, int $id = 0): array
    {
        return static::find()
            ->where(['deleted' => 0, 'category' => $category])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $category
     * @param int $limit
     * @param int $id
     * @return array
     */
    public static function findPopular(string $category, int $limit, int $id = 0): array
    {
        return static::find()
            ->where(['deleted' => 0, 'category' => $category])
            ->andWhere(['>', 'ratingAvg', '0.0'])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('ratingAvg DESC, ratings DESC, modified DESC')
            ->limit($limit)
            ->all();
    }

    public static function findAllAtoZ(string $category): array
    {
        return static::find()
            ->select('id, category, title, url, modified')
            ->where([
                'deleted' => 0,
                'category' => $category
            ])
            ->orderBy(
                'title ASC'
            )
            ->all();
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
                'value' => Html::a($this->publisher, ['catalog/index', 'category' => $this->category, 'publisher' => $this->publisher])
            ];
        }
        if (!empty($this->autor)) {
            $infos[] = [
                'key' => 'autor',
                'label' => 'Autor',
                'value' => Html::a($this->autor, ['catalog/index', 'category' => $this->category, 'autor' => $this->autor])
            ];
        }
        if (!empty($this->series)) {
            $infos[] = [
                'key' => 'series',
                'label' => 'Serie',
                'value' => Html::a($this->series, ['catalog/index', 'category' => $this->category, 'series' => $this->series])
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
        if (!empty($this->upc)) {
            $infos[] = [
                'key' => 'upc',
                'label' => 'UPC',
                'value' => $this->upc
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
                'value' => Yii::t('app', $this->language)
            ];
        }
        return $infos;
    }

    public function isNew()
    {
        $now = time(); // or your date as well
        $date = strtotime($this->modified);
        $days = round(($now - $date) / (60 * 60 * 24));
        return $days < 60;
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
        $relpath = Media::getImage('catalog', $this->id);
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
        $ids = Yii::$app->session->get('HITS_CATALOG_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_CATALOG_IDS', $ids);
        }
    }

    public function getTracklistArray()
    {
        if (empty($this->tracklist)) {
            return '';
        }
        $tracklist = [];
        $lines = explode("\n", $this->tracklist);
        foreach ($lines as $line) {

            $line = trim($line);

            $match = preg_match("/^([0-9]+)\.\s(.*)\s(\(?[0-9]+\:?[0-9]+\)?)$/", $line, $matches);
            #$match = preg_match('/^([0-9]+\.){1} (.+){1} (\(?[0-9]+:[0-9]+\)?){1}$/U', $line, $matches);
            if ($match > 0) {
                $tracklist[] = [
                    'number' => trim($matches[1]),
                    'title' => trim($matches[2]),
                    'duration' => trim(str_replace(['(', ')'], '', $matches[3])),
                ];
            }

            /*
            $parts = explode("\t", $line);
            if (count($parts) > 1) {
                $parts = array_map('trim', $parts);
                if (preg_match('/^[0-9]+\. .+/', $parts[0], $matches)) {
                    $pos = strpos($parts[0], '.');
                    if ($pos !== false) {
                        $tracklist[] = [
                            'number' => substr($parts[0], 0, $pos),
                            'title' => trim(substr($parts[0], $pos + 1)),
                            'duration' => $parts[1],
                        ];
                    }
                }
            }
            */
        }
        return $tracklist;
    }

    public function getProductNumber(): string
    {
        $numbers = $this->getProductNumbers();
        return $numbers[0] ?? '';
    }

    public function getProductNumbers(): array
    {
        $numbers = [];
        if (!empty($this->ean)) {
            $numbers[] = $this->ean;
        }
        if (!empty($this->isbn)) {
            $numbers[] = $this->isbn;
        }
        if (!empty($this->asin)) {
            $numbers[] = $this->asin;
        }
        if (!empty($this->upc)) {
            $numbers[] = $this->upc;
        }
        return array_unique($numbers);
    }
}
