<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Rating extends ActiveRecord
{
    public static $ALLOWED = [
        'advertisement',
        'album',
        'blog',
        'catalog',
        'fingering',
        'glossar',
        'lesson',
        'website',
        'video',
        'joke',
        'quote'
    ];

    public static function findLatestRatings(int $limit)
    {
        return static::find()
            ->with('search')
            ->select(['r1.tableName', 'r1.tableId', 'r1.value', 'r1.modified'])
            ->from(['r1' => 'rating'])
            ->leftJoin(['r2' => 'rating'], 'r1.tableName = r2.tableName AND  r1.tableId = r2.tableId AND r1.id < r2.id')
            ->where(['r2.id' => null])
            ->andWhere(['>', 'r1.tableId', 0])
            ->orderBy('r1.created DESC')
            ->limit($limit)
            ->all();
    }

    public function getSearch()
    {
        return $this->hasOne(Search::class, ['id' => 'tableId', 'tableName' => 'tableName']);
    }

    public static function synchronizeRatings(): int
    {
        // reset entries
        foreach (self::$ALLOWED as $table) {
            Yii::$app->db->createCommand()->update($table, [
                'ratings' => 0,
                'ratingAvg' => 0.0
            ])->execute();
        }

        // load data
        $sql = <<<SQL
                SELECT 
                tableName, 
                tableId, 
                COUNT(*) AS ratings, 
                ROUND(AVG(value),2) AS ratingAvg
            FROM rating
            GROUP BY tableName, tableId
            ORDER BY tableName
        SQL;
        $ratings = Yii::$app->db->createCommand($sql)->queryAll();

        $count = 0;

        // update data
        foreach ($ratings as $rating) {
            if (!in_array($rating['tableName'], self::$ALLOWED)) {
                continue;
            }
            $count += Yii::$app->db->createCommand()->update(
                $rating['tableName'],
                [
                    'ratings' => $rating['ratings'],
                    'ratingAvg' => $rating['ratingAvg']
                ],
                [
                    'id' => $rating['tableId']
                ]
            )->execute();
        }

        return $count;
    }

}
