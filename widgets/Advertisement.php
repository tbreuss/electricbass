<?php

namespace app\widgets;

use yii\base\Widget;

class Advertisement extends Widget
{

    public $title = 'Kleinanzeigen';

    public $limit = 4;

    public function run()
    {
        $rows = \app\models\Advertisement::findLatestAsArray($this->limit);
        return $this->render('advertisement', array(
            'title' => $this->title,
            'rows' => $rows
        ));
    }

}
