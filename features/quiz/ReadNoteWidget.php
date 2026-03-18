<?php

namespace app\features\quiz;

use yii\base\Widget;

class ReadNoteWidget extends Widget
{
    public function run(): string
    {
        $notes = ['c', 'd', 'e'];
        $length = 10;

        return $this->render('read-note-widget', [
            'notes' => $notes,
            'length' => $length,
        ]);
    }
}
