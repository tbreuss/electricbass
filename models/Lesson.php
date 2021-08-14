<?php

namespace app\models;

use app\components\ActiveRecord;
use app\traits\SimilarModelsByTags;
use Yii;
use yii\helpers\Url;

class Lesson extends ActiveRecord
{
    use SimilarModelsByTags;

    public static function findAllChildren($path = '')
    {
        if (!empty($path)) {
            $path .= '/';
        }
        $models = self::find()
            ->select('id, title, abstract, url')
            ->where("deleted = 0 AND url REGEXP '^{$path}[a-z0-9_-]+$'")
            ->orderBy('autosort ASC')
            ->all();
        return $models;
    }

    public static function findParents($path)
    {
        $parts = array_filter(explode('/', $path));

        $segment = '/';
        $delim = '';
        foreach ($parts as $part) {
            $segment .= $delim . $part;
            $conditions[] = "url='{$segment}'";
            $delim = '/';
        }

        $models = self::find()
            ->select('id, title, navtitle, url')
            ->where("deleted = 0 AND (" . implode(' OR ', $conditions) . ")")
            ->all();

        return $models;
    }

    /**
     * @param int $id
     * @param int $limit
     * @return array
     */
    public static function findLatest(int $id, int $limit = 10): array
    {
        return static::find()
            ->where(['deleted' => 0])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $alias
     * @return string
     */
    public function getDefaultImage(string $alias = ''): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function hasDefaultImage(): bool
    {
        return false;
    }

    public function increaseHits()
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_LESSON_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_LESSON_IDS', $ids);
        }
    }

    public function getMenuTitle()
    {
        return empty($this->navtitle) ? $this->title : $this->navtitle;
    }

}
