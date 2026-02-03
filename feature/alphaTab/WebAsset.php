<?php

namespace app\feature\alphaTab;

use yii\web\AssetBundle;

final class WebAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/feature/alphaTab/assets';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $js = [
        'alphaTab@1.8.1.min.js',
    ];
}
