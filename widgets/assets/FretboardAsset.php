<?php

namespace app\widgets\assets;

use yii\web\AssetBundle;

final class FretboardAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/assets/fretboard';
    public $css = ['main.css'];
    public $js = ['main.js'];
}
