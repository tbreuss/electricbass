<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/app/dist';
    public $css = [
        /* @phpstan-ignore-next-line */
        YII_ENV_DEV ? 'main.css' : 'main.min.css'
    ];
    public $js = [
        /* @phpstan-ignore-next-line */
        YII_ENV_DEV ? 'main.js' : 'main.min.js'
    ];
}
