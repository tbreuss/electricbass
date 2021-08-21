<?php

namespace app\models;

use app\traits\SimilarModelsByTags;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $root
 * @property string $position
 * @property string $notes
 * @property string $strings
 * @property string $abstract
 * @property string $category
 * @property string $url
 */
class Fingering extends ActiveRecord
{
    use SimilarModelsByTags;

    /**
     * @param int|string $id
     * @return null|self
     */
    public static function findOneOrNull($id)
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

    public function increaseHits()
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_FINGERING_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_FINGERING_IDS', $ids);
        }
    }

}
