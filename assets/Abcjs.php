<?php

namespace app\assets;

use yii\web\AssetBundle;

final class Abcjs extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/assets/abcjs';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $js = [
        'abcjs-basic.min.js',
    ];

    /** @var string[] */
    public $depends = [];
}
