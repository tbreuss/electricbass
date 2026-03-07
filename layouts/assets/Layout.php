<?php

namespace app\layouts\assets;

use yii\web\AssetBundle;

final class Layout extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/layouts/assets/dist';

    /** @var string[] */
    public $css = [
        YII_ENV_DEV ? 'main.css' : 'main.min.css',
        YII_ENV_DEV ? 'unpoly.css' : 'unpoly.min.css',
    ];

    /** @var string[] */
    public $js = [
        YII_ENV_DEV ? 'main.js' : 'main.min.js',
        YII_ENV_DEV ? 'unpoly.js' : 'unpoly.min.js',
    ];
}
