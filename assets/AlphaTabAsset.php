<?php

namespace app\assets;

use yii\web\AssetBundle;

class AlphaTabAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/alphaTab';
    public $baseUrl = '@web';
    public $js = [
        'JavaScript/AlphaTab.js',
        'JavaScript/jquery.alphaTab.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
