<?php

namespace app\widgets;

use yii\base\Widget;

class Articles extends Widget
{
    public $title = null;
    public $tableName = '';
    public $limit = 4;
    public $orderBy = 'created DESC';
    public $excludeTableNames = ['advertisement', 'glossar'];

    public function init()
    {
        $this->limit = intval($this->limit);
    }

    public function run(): string
    {
        $query = \app\models\Search::find();

        if (!empty($this->excludeTableNames)) {
            $query->where(['not in', 'tableName', $this->excludeTableNames]);
        }

        if (!empty($this->tableName)) {
            $query->andWhere('tableName=:tableName', [
                'tableName' => $this->tableName
            ]);
        }

        if (!empty($this->limit)) {
            $query->limit($this->limit);
        }

        if (!empty($this->orderBy)) {
            $query->orderBy($this->orderBy);
        }

        $models = $query->all();

        if (empty($models)) {
            return '';
        }

        return $this->render('articles', array(
            'title' => $this->title,
            'models' => $models
        ));
    }

}
