<?php

namespace app\widgets;

use yii\base\Widget;

class Advertisement extends Widget
{

    public string $title = 'Kleinanzeigen';
    public int $limit = 4;

    public function run(): string
    {
        $rows = \app\models\Advertisement::findLatestAsArray($this->limit);
        return $this->render('advertisement', array(
            'title' => $this->title,
            'rows' => $rows
        ));
    }

}
