<?php

namespace app\widgets;

use app\widgets\assets\MetronomeAsset;
use yii\base\Widget;

/**
 * @see https://github.com/seanwayland/waylonome
 * @see https://seanwayland.github.io/waylonome
 * @see https://github.com/grantjames/metronome
 */
class Metronome extends Widget
{
    public function init(): void
    {
        MetronomeAsset::register($this->getView());
    }

    public function run(): string
    {
        return $this->render('metronome');
    }
}
