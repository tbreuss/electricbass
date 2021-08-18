<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Media;
use Yii;
use yii\db\Query;
use yii\helpers\Url;

/**
 * @property int $id
 * @property string $context
 * @property string $tableName
 * @property string $contextKey
 * @property string $category
 */
class Search extends ActiveRecord
{
    public static function findLatestGroupedBy(array $contexts)
    {
        $models = [];
        foreach ($contexts as $context => $limit) {
            $models[$context] = Search::find()
                ->where(['context' => $context, ])
                ->orderBy('modified DESC')
                ->limit($limit)
                ->all();
        }
        return $models;
    }

    public static function getQueryObject()
    {
        $query = (new Query())
            ->select(['tableName', 'tableId', 'id', 'url', 'category', 'modified'])
            ->from('search')
            ->orderBy('modified DESC');
        return $query;
    }

    public function hasFoto()
    {
        return false;
    }

    public function getDefaultImage(string $alias = ''): string
    {
        switch ($this->tableName) {
            case 'advertisement':
                $webRoot = Yii::getAlias('@webroot/');
                $pattern = sprintf('%smedia/kleinanzeigen/%d-*.*', $webRoot, $this->id);
                $result = glob($pattern, GLOB_NOSORT);
                if (isset($result[0])) {
                    return str_replace($webRoot, '', $result[0]);
                }
                return '';
            case 'album':
            case 'catalog':
                $image = Media::getImage($this->tableName, $this->id);
                if (strlen($alias) > 0) {
                    $alias = rtrim($alias, '/') . '/';
                    return Yii::getAlias($alias . $image);
                }
                return $image;
            case 'blog':
            case 'fingering':
            case 'glossar':
            case 'lesson':
            case 'website':
                return '';
            case 'video':
                [$platform, $key] = explode(':', $this->contextKey);
                if ($platform === 'youtube') {
                    return "https://img.youtube.com/vi/$key/mqdefault.jpg";
                }
                return '';
        }
        return '';
    }

    public function hasDefaultImage(): bool
    {
        $url = $this->getDefaultImage();
        return strlen($url) > 0;
    }

    public function getUrlToOverview($scheme = false)
    {
        switch ($this->tableName) {
            case 'advertisement':
                return Url::to(['/advertisement/index'], $scheme);
            case 'album':
                return Url::to(['/album/index'], $scheme);
            case 'blog':
                return Url::to(['/blog/index'], $scheme);
            case 'catalog':
                return Url::to(['/catalog/index', 'category' => $this->category], $scheme);
            case 'fingering':
                return Url::to(['/fingering/index'], $scheme);
            case 'glossar':
                return Url::to(['/glossar/index', 'category' => strtolower($this->category)], $scheme);
            case 'lesson':
                return Url::to(['/lesson/index', 'path' => $this->category], $scheme);
            case 'website':
                return Url::to(['/website/index'], $scheme);
            case 'video':
                return Url::to(['/video/index'], $scheme);
        }
        return '';
    }

    public function getContextText()
    {
        switch ($this->context) {
            case 'advertisement':
                return 'Bass-Inserat';
            case 'album':
                return 'Bass-Album';
            case 'blog':
                return 'Bass-Blog';
            case 'buch':
                return 'Bass-Buch';
            case 'catalog':
                return 'Bass-Katalog';
            case 'dvd':
                return 'Bass-Lehrbuch mit DVD';
            case 'fingering':
                return 'Bass-Fingersatz';
            case 'glossar':
                return 'Bass-Glossar';
            case 'lehrbuch':
                return 'Bass-Lehrbuch';
            case 'lesson':
                return 'Bass-Lektion';
            case 'website':
                return 'Bass-Website';
            case 'video':
                return 'Bass-Video';
        }
        return '';
    }

    public function getContextTextPlural()
    {
        switch ($this->context) {
            case 'advertisement':
                return 'Bass-Inserate';
            case 'album':
                return 'Bass-Alben';
            case 'blog':
                return 'Bass-Blogposts';
            case 'buch':
                return 'Bass-Bücher';
            case 'catalog':
                return 'Bass-Kataloge';
            case 'dvd':
                return 'Bass-Lehrbücher mit DVD';
            case 'fingering':
                return 'Bass-Fingersätze';
            case 'glossar':
                return 'Glossareinträge';
            case 'lehrbuch':
                return 'Bass-Lehrbücher';
            case 'lesson':
                return 'Bass-Lektionen';
            case 'website':
                return 'Bass-Websites';
            case 'video':
                return 'Bass-Videos';
        }
        return '';
    }

    public function getLastModAsAtom()
    {
        $date = empty($this->modified) ? date('Y-m-d H:i:s') : $this->modified;
        return date(\DateTime::ATOM, strtotime($date));
    }
}
