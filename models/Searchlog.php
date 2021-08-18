<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property string $created
 * @property string $modified
 */
class Searchlog extends ActiveRecord
{
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'term' => 'Suchbegriff',
            'frequency' => 'Häufigkeit',
        );
    }

    public static function findLastSearchQueries($limit = 10)
    {
        return self::find()
            ->orderBy('modified DESC')
            ->limit($limit)
            ->all();

    }

    public static function findPopularSearchQueries($limit = 10)
    {
        return self::find()
            ->orderBy('frequency DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @param string $term
     * @param integer $results
     */
    public static function addTerm($term, $results)
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
     * @return bool
     */
    public function beforeSave($insert)
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
