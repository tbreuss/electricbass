<?php

namespace app\models;

use app\components\ActiveRecord;
use app\helpers\Html;
use Yii;
use yii\web\NotFoundHttpException;

class Glossar extends ActiveRecord
{
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'category' => 'Kategorie',
            'title' => 'Titel',
            'url' => 'URL',
            'abstract' => 'Kurztext',
            'content' => 'Langtext',
            'frequency' => 'Häufigkeit',
        );
    }

    public function rules()
    {
        return array(
            array('category', 'filter', 'filter' => 'trim'),
            array('title', 'required'),
            array('url', 'required'),
            array('abstract', 'required'),
            array('content', 'required'),
        );
    }

    public function findAllByCategory($category)
    {
        $condition = '';
        $params = array();
        if (!empty($category)) {
            $condition = ' AND category=:category';
            $params = array(
                ':category' => strtolower($category)
            );
        }
        $criteria = array(
            'condition' => 'deleted=0 AND hidden=0' . $condition,
            'order' => 'autosort',
            'params' => $params
        );
        return $this->findAll($criteria);
    }

    public static function findOneOrNull($id)
    {
        $glossar = Glossar::find()->where(['deleted' => 0, 'url' => $id])->one();
        if ($glossar) {
            return $glossar;
        }
        $glossar = Glossar::find()->where(['deleted' => 0, 'id' => $id])->one();
        if ($glossar) {
            return $glossar;
        }
        return null;
    }

    /**
     * @param int|string $id
     * @return null|Glossar
     * @throws NotFoundHttpException
     */
    public static function findOneOrThrowException($id)
    {
        $glossar = static::findOneOrNull($id);
        if (is_null($glossar)) {
            throw new NotFoundHttpException();
        }
        return $glossar;
    }

    public function findNextOneOrNull()
    {
        $condition = 'autosort>:autosort AND deleted=0 AND hidden=0';
        $params = [':autosort' => $this->autosort];
        $record = static::find()->where($condition, $params)->orderBy('autosort ASC')->one();
        return $record;
    }

    public function findPreviousOneOrNull()
    {
        $condition = 'autosort<:autosort AND deleted=0 AND hidden=0';
        $params = [':autosort' => $this->autosort];
        $record = static::find()->where($condition, $params)->orderBy('autosort DESC')->one();
        return $record;
    }

    public static function queryAllCategories()
    {
        $command = Yii::$app->db->createCommand('SELECT DISTINCT category FROM glossar WHERE deleted=0 AND hidden=0 ORDER BY category');
        return $command->queryAll();
    }

    public function getDefaultImage(string $alias = ''): string
    {
        return '';
    }

    public function hasDefaultImage(): bool
    {
        return false;
    }

    public function increaseHits()
    {
        // IDs in Session speichern
        $ids = Yii::$app->session->get('HITS_GLOSSAR_IDS', []);
        if(!in_array($this->id, $ids)) {
            $this->updateCounters(['hits' => 1]);
            $ids[] = $this->id;
            Yii::$app->session->set('HITS_GLOSSAR_IDS', $ids);
        }
    }

}
