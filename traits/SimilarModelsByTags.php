<?php

namespace app\traits;

/**
 * @property string $tags
 */
trait SimilarModelsByTags
{
    /**
     * @param int $id
     * @param string[] $tags
     * @param int $limit
     * @return array
     * @phpstan-return \app\models\Album[]|\app\models\Blog[]|\app\models\Catalog[]|\app\models\Fingering[]|\app\models\Lesson[]|\app\models\Video[]|\app\models\Website[]
     * @throws \yii\db\Exception
     */
    public static function findSimilars(int $id, array $tags, int $limit = 10): array
    {
        $ids = self::findSimilarsIds($id, $tags);
        if (empty($ids)) {
            return [];
        }

        $query = self::find()
            ->andWhere('deleted=0 OR deleted IS NULL')
            ->andWhere(['id' => $ids])
            ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $ids) . ')')])
            ->limit($limit);

        if (self::hasArchivedColumn()) {
            $query->andWhere('archived=0 OR archived IS NULL');
        }

        return $query->all();
    }

    /**
     * @param int $id
     * @param string[] $tags
     * @return int[]
     * @throws \yii\db\Exception
     */
    protected static function findSimilarsIds(int $id, array $tags): array
    {
        if (empty($tags)) {
            return [];
        }

        $tags = array_map('trim', $tags);
        $tags = "'" . implode("', '", $tags) . "'";

        $tableName = trim(self::tableName(), '{%}');

        $archivedConstraint = self::hasArchivedColumn() ? 'AND (archived = 0 OR archived IS NULL)' : '';

        // See:
        // https://stackoverflow.com/questions/17942508/sql-split-values-to-multiple-rows
        // http://www.petefreitag.com/item/315.cfm

        $sql = <<<SQL
            SELECT {$tableName}.*, COUNT({$tableName}_tag.name) AS counter
             FROM {$tableName}, (
                SELECT DISTINCT
                  SUBSTRING_INDEX(SUBSTRING_INDEX({$tableName}.tags, ',', numbers.n), ',', -1) name
                FROM
                  (SELECT 1 n UNION ALL
                   SELECT 2 UNION ALL 
                   SELECT 3 UNION ALL
                   SELECT 4 UNION ALL 
                   SELECT 5) numbers INNER JOIN {$tableName}
                  ON CHAR_LENGTH({$tableName}.tags)
                     -CHAR_LENGTH(REPLACE({$tableName}.tags, ',', ''))>=numbers.n-1
                ) AS {$tableName}_tag
            WHERE 1
            {$archivedConstraint}  
            AND (deleted = 0 OR deleted IS NULL)
            AND FIND_IN_SET({$tableName}_tag.name, {$tableName}.tags) > 0
            AND {$tableName}_tag.name IN ({$tags})
            AND {$tableName}.id <> :id
            GROUP BY {$tableName}.title, {$tableName}.id
            HAVING counter>0
            ORDER BY counter DESC, created DESC
            LIMIT 10
        SQL;

        return self::getDb()->createCommand($sql, [
            'id' => $id,
        ])->queryColumn();
    }

    /**
     * @param string[] $filters
     * @return string[]
     */
    public function getTagsAsArray(array $filters = []): array
    {
        if (strlen($this->tags) === 0) {
            return [];
        }
        $tags = array_map('trim', explode(',', $this->tags));
        if (empty($filters)) {
            return $tags;
        }
        return array_filter($tags, function ($value) use ($filters) {
            return !in_array($value, $filters);
        });
    }

    protected static function hasArchivedColumn(): bool
    {
        return self::getTableSchema()->getColumn('archived') !== null;
    }
}
