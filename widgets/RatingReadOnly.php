<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

class  RatingReadOnly extends Widget
{
    public $style;
    public $tableName;
    public $tableId;
    public $ratingCount = null;
    public $ratingAverage = null;
    public function run()
    {
        if (!in_array($this->tableName, ['advertisement', 'album', 'blog', 'catalog', 'fingering', /*'glossar', */ 'lesson', 'website', 'video'])) {
            return '';
        }

        if (($this->ratingCount === null) && ($this->ratingAverage === null)) {
            $data = $this->loadData();
            $this->ratingCount = $data['ratingCount'];
            $this->ratingAverage = $data['ratingAverage'];
        }

        return $this->render('rating_readonly', [
            'style' => $this->getStyle(),
            'ratingCount' => $this->ratingCount,
            'ratingAverage' => $this->ratingAverage
        ]);
    }

    private function getStyle()
    {
        if (in_array($this->style, ['left', 'right', 'none'])) {
            return $this->style;
        }
        return 'none';
    }

    public function loadData()
    {
        $sql = '
            select count(id) as count, avg(value) as average
            from rating
            where tableName=:tableName
            and tableId=:tableId
        ';

        $row = Yii::$app->db->createCommand($sql)
            ->bindValue(':tableName', $this->tableName)
            ->bindValue(':tableId', $this->tableId)
            ->queryOne();

        $data = [
            'ratingCount' => (int)0,
            'ratingAverage' => (float)0
        ];

        if (is_array($row)) {
            $data['ratingCount'] = (int)$row['count'];
            $data['ratingAverage'] = round($row['average'], 1);
        }
        return $data;
    }

}
