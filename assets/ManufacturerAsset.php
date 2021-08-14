<?php

namespace app\assets;

use yii\web\AssetBundle;

class ManufacturerAsset extends AssetBundle
{
    public $sourcePath = '@app/views/manufacturer';
    public $css = [
        'index.css',
    ];
    public $js = [
        'mithril-2.0.4.js',
        'index.js'
    ];
}
