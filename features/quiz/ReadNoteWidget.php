<?php

namespace app\features\quiz;

use yii\base\Widget;

class ReadNoteWidget extends Widget
{
    public function run(): string
    {
        return $this->render('read-note-widget', []);
    }
}
