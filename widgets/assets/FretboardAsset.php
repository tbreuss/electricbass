<?php

namespace app\widgets\assets;

use yii\web\AssetBundle;

final class FretboardAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/widgets/assets/fretboard';
    /** @var string[] */
    public $css = ['main.css'];
    /** @var string[] */
    public $js = ['main.js'];
}
