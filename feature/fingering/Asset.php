<?php

namespace app\feature\fingering;

use yii\web\AssetBundle;

final class Asset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/feature/fingering/assets';
    /** @var string[] */
    public $css = ['main.css'];
    /** @var string[] */
    public $js = [];
}
