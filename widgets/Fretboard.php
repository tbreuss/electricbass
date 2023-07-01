<?php

namespace app\widgets;

use yii\base\Widget;

final class Fretboard extends Widget
{
    public $showDots = true;
    public $showFretNumbers = true;
    public $showStringNames = true;
    public $notes = [];

    public function run(): string
    {
        return $this->render('fretboard', [
            'config' => [
                'showDots' => $this->showDots,
                'showFretNumbers' => $this->showFretNumbers,
                'showStringNames' => $this->showStringNames,
            ],
            'notes' => $this->notes,
        ]);
    }
}
