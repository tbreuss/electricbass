<?php

namespace app\features\alphaTab;

use yii\web\AssetBundle;

final class WebAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/features/alphaTab/assets';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $js = [
        'alphaTab.min.js',
    ];
}
