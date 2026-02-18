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
        'alphaTab.min.js',
    ];
}
