<?php

namespace app\features\quiz;

use yii\base\Widget;

final class ReadNoteWidget extends Widget
{
    public array $notes = [];
    public int $length = 0;
    public ?string $lead = null;
    public ?string $hint = null;
    public ?string $categoryUrl = null;
    public ?string $nextQuizUid = null;

    public function run(): string
    {
        return $this->render('read-note-widget', [
            'notes' => $this->notes,
            'length' => $this->length,
            'lead' => $this->lead,
            'hint' => $this->hint,
            'categoryUrl' => $this->categoryUrl,
            'nextQuizUid' => $this->nextQuizUid,
        ]);
    }
}
