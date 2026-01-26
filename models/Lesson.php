<?php

namespace app\models;

use app\components\ActiveRecord;
use app\traits\SimilarModelsByTags;
use app\traits\WithChanges;
use Yii;

/**
 * @property int $id
 * @property string $title
 * @property string $abstract
 * @property string $url
 * @property string $menuTitle
 * @property int $tableOfContents
 * @property int $headingPermalink
 */
final class Lesson extends ActiveRecord
{
    use SimilarModelsByTags;
    use WithChanges;

    public static function findByUrl(string $url): ?self
    {
        return self::find()
            ->where(['url' => $url])
            ->one();
    }

    /**
     * @return Lesson[]
     */
    public static function findAllChildren(string $path = ''): array
    {
        if (!empty($path)) {
            $path .= '/';
        }
        return self::find()
            ->select('id, title, abstract, url')
            ->where("deleted = 0 AND url REGEXP '^{$path}[a-z0-9_-]+$'")
            ->orderBy('autosort ASC')
            ->all();
    }

    /**
     * @return Lesson[]
     */
    public static function findParents(string $path): array
    {
        $parts = array_filter(explode('/', $path));

        $segment = '/';
        $delim = '';
        $conditions = [];
        foreach ($parts as $part) {
            $segment .= $delim . $part;
            $conditions[] = "url='{$segment}'";
            $delim = '/';
        }

        return self::find()
            ->select('id, title, navtitle, url')
            ->where("deleted = 0 AND (" . implode(' OR ', $conditions) . ")")
            ->all();
    }

    /**
     * @return Lesson[]
     */
    public static function findLatest(int $id, int $limit = 10): array
    {
        return self::find()
            ->where(['deleted' => 0])
            ->andWhere(['<>', 'id', $id])
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    public function getDefaultImage(string $alias = ''): string
    {
        return '';
    }

    public function hasDefaultImage(): bool
    {
        return false;
    }

    public function increaseHits(): void
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_LESSON_IDS', []);
        if (!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_LESSON_IDS', $ids);
        }
    }

    public function getMenuTitle(): string
    {
        return empty($this->navtitle) ? $this->title : $this->navtitle;
    }
}
