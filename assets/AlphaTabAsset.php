<?php

namespace app\assets;

use yii\web\AssetBundle;

final class AlphaTabAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/assets/alphaTab';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $js = [
        'alphaTab@1.8.0.min.js',
    ];
}
