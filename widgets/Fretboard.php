<?php

namespace app\widgets;

use app\widgets\assets\FretboardAsset;
use yii\base\Widget;

final class Fretboard extends Widget
{
    public $colors = 'default'; // default|intervals
    public $showDots = true;
    public $showFretNumbers = true;
    public $showStringNames = true;
    public $notes = [];

    public function init(): void
    {
        FretboardAsset::register($this->getView());
    }

    public function run(): string
    {
        return $this->render('fretboard', [
            'config' => [
                'colors' => $this->colors,
                'showDots' => $this->showDots,
                'showFretNumbers' => $this->showFretNumbers,
                'showStringNames' => $this->showStringNames,
            ],
            'notes' => $this->notes,
        ]);
    }
}
