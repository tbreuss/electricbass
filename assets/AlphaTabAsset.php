<?php

namespace app\assets;

use yii\web\AssetBundle;

class AlphaTabAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/assets/alphaTab';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $js = [
        'JavaScript/AlphaTab.js',
        'JavaScript/jquery.alphaTab.js'
    ];

    /** @var string[] */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
