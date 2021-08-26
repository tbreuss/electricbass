<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property string $term
 * @property int $results
 * @property int|Expression $frequency
 * @property string $created
 * @property string $modified
 */
final class Searchlog extends ActiveRecord
{
    /**
     * @return array<string, string>
     */
    public function attributeLabels(): array
    {
        return array(
            'id' => 'Id',
            'term' => 'Suchbegriff',
            'frequency' => 'Häufigkeit',
        );
    }

    /**
     * @return Searchlog[]
     */
    public static function findLastSearchQueries(int $limit = 10): array
    {
        return self::find()
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @return Searchlog[]
     */
    public static function findPopularSearchQueries(int $limit = 10): array
    {
        return self::find()
            ->orderBy('frequency DESC')
            ->limit($limit)
            ->all();
    }

    public static function addTerm(string $term, int $results): void
    {
        $cookieSearches = Yii::$app->session->get('SEARCHLOG_TERMS', []);

        if (!in_array($term, $cookieSearches)) {
            $model = self::find()
                ->where('term=:term', [':term' => $term])
                ->one();

            if (is_null($model)) {
                $model = new self();
            }

            $model->term = $term;
            $model->results = $results;
            $model->frequency = new Expression('frequency+1');
            $model->save();

            $cookieSearches[] = $term;
            Yii::$app->session->set('SEARCHLOG_TERMS', $cookieSearches);
        }
    }

    /**
     * @param bool $insert
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created = new Expression('NOW()');
                $this->modified = new Expression('NOW()');
            } else {
                $this->modified = new Expression('NOW()');
            }
            return true;
        }
        return false;
    }
}
