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
 * @property string $artist
 * @property string $title
 * @property string $fullTitle
 * @property string $text
 * @property string $url
 * @property string $modified
 */
final class Album extends ActiveRecord
{
    use SimilarModelsByTags;

    public function getFullTitle(): string
    {
        return $this->title . ' - ' . $this->artist;
    }

    /**
     * @phpstan-param array<string, string> $filter
     */
    public static function getActiveDataProvider(array $filter): ActiveDataProvider
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

        $where = array_merge(['deleted' => 0], $filter);

        $query = self::find()
            ->select('id, title, artist, abstract, url')
            ->where($where)
            ->orderBy($sort->orders);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 24,
            ],
            'sort' => $sort,
        ]);
    }

    /**
     * @param int|string $id
     */
    public static function findOneOrNull($id): ?Album
    {
        $model = self::find()->where(['deleted' => 0, 'url' => $id])->one();
        if ($model) {
            return $model;
        }
        $model = self::find()->where(['deleted' => 0, 'id' => $id])->one();
        if ($model) {
            return $model;
        }
        return null;
    }

    /**
     * @return Album[]
     */
    public static function findLatest(int $limit, int $id = 0): array
    {
        return self::find()
            ->where(['deleted' => 0])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @return Album[]
     */
    public static function findPopular(int $limit, int $id = 0): array
    {
        return self::find()
            ->where(['deleted' => 0])
            ->andWhere(['>', 'ratingAvg', 0])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('ratingAvg DESC, ratings DESC, modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @return Album[]
     */
    public static function findAllAtoZ(): array
    {
        return self::find()
            ->select('id, artist, title, url, modified')
            ->where([
                'deleted' => 0
            ])
            ->orderBy(
                'artist ASC, title ASC'
            )
            ->all();
    }

    /**
     * @phpstan-return array<int, array{"key": string, "label": string, "value": string}>
     */
    public function getProductInfos(): array
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
                'label' => 'Erscheinungsdatum',
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
        if (!empty($this->label)) {
            $infos[] = [
                'key' => 'label',
                'label' => 'Label',
                'value' => $this->label
            ];
        }
        if (!empty($this->copyright)) {
            $infos[] = [
                'key' => 'copyright',
                'label' => 'Copyright',
                'value' => '(c) ' . $this->copyright
            ];
        }
        if (!empty($this->totalLength)) {
            $infos[] = [
                'key' => 'totalLength',
                'label' => 'Gesamtlänge',
                'value' => $this->totalLength
            ];
        }
        if (!empty($this->genres)) {
            $infos[] = [
                'key' => 'genres',
                'label' => 'Genres',
                'value' => $this->genres
            ];
        }
        if (!empty($this->quantity)) {
            $infos[] = [
                'key' => 'quantity',
                'label' => 'Anzahl Tonträger',
                'value' => $this->quantity
            ];
        }
        return $infos;
    }

    public function getDefaultImage(string $alias = ''): string
    {
        if (!empty($alias)) {
            $alias = rtrim($alias, '/') . '/';
        }

        $image = '';
        $relpath = Media::getImage('album', $this->id);
        if (!empty($relpath)) {
            $image = $alias . $relpath;
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
        $ids = Yii::$app->session->get('HITS_ALBUM_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_ALBUM_IDS', $ids);
        }
    }

    /**
     * @phpstan-return array<int, array{"number": string, "title": string, "duration": string}>
     */
    public function getTracklistArray(): array
    {
        if (empty($this->tracklist)) {
            return [];
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

    public function isNew(): bool
    {
        $now = time(); // or your date as well
        $date = strtotime($this->modified);
        $days = round(($now - $date) / (60 * 60 * 24));
        return $days < 60;
    }
}
