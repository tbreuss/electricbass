<?php

namespace app\widgets;

use JetBrains\PhpStorm\ArrayShape;
use Yii;
use yii\base\Widget;

final class  RatingReadOnly extends Widget
{
    public string $style = 'none';
    public string $tableName = '';
    public int $tableId = 0;
    public ?int $ratingCount = null;
    public ?float $ratingAverage = null;

    public function run(): string
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

    private function getStyle(): string
    {
        if (in_array($this->style, ['left', 'right', 'none'])) {
            return $this->style;
        }
        return 'none';
    }

    /**
     * @phpstan-return array{"ratingCount": int, "ratingAverage": float}
     * @throws \yii\db\Exception
     */
    public function loadData(): array
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
