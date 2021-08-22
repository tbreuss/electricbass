<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/assets/app/dist';

    /** @var string[] */
    public $css = [
        /* @phpstan-ignore-next-line */
        YII_ENV_DEV ? 'main.css' : 'main.min.css'
    ];

    /** @var string[] */
    public $js = [
        /* @phpstan-ignore-next-line */
        YII_ENV_DEV ? 'main.js' : 'main.min.js'
    ];
}
