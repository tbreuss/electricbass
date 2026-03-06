<?php

namespace app\features\fingering;

use yii\web\AssetBundle;

final class Asset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/features/fingering/assets';
    /** @var string[] */
    public $css = ['main.css'];
    /** @var string[] */
    public $js = [];
}
