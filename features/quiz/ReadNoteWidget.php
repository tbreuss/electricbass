<?php

namespace app\features\quiz;

use yii\base\Widget;

class ReadNoteWidget extends Widget
{
    public array $notes = [];
    public int $length = 0;
    public ?string $nextUrl = null;

    public function run(): string
    {
        return $this->render('read-note-widget', [
            'notes' => $this->notes,
            'length' => $this->length,
            'nextUrl' => $this->nextUrl,
        ]);
    }
}
