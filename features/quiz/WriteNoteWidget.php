<?php

namespace app\features\quiz;

use yii\base\Widget;

class WriteNoteWidget extends Widget
{
    public function run(): string
    {
        return $this->render('write-note-widget', []);
    }
}
