<?php

namespace app\features\quiz;

use yii\base\Widget;

class ReadNoteWidget extends Widget
{
    public function run(): string
    {
        $quiz = ['c', 'd', 'e', 'c', 'e', 'd', 'c', 'e', 'd', 'c'];
        return $this->render('read-note-widget', [
            'quiz' => $quiz,
        ]);
    }
}
