<?php

namespace app\features\metronome;

/**
 * @see https://github.com/seanwayland/waylonome
 * @see https://seanwayland.github.io/waylonome
 * @see https://github.com/grantjames/metronome
 */
class Widget extends \yii\base\Widget
{
    public function init(): void
    {
        Asset::register($this->getView());
    }

    public function run(): string
    {
        return $this->render('widget');
    }
}
