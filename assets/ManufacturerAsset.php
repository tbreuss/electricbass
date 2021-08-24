<?php

namespace app\assets;

use yii\web\AssetBundle;

final class ManufacturerAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/views/manufacturer';

    /** @var string[] */
    public $css = [
        'index.css',
    ];

    /** @var string[] */
    public $js = [
        'mithril-2.0.4.js',
        'index.js'
    ];
}
