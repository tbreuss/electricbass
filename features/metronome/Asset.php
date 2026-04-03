<?php

namespace app\features\metronome;

use yii\web\AssetBundle;

final class Asset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/features/metronome/assets';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $css = [
        'styles.css'
    ];

    /** @var string[] */
    public $js = [
        'metronome.js',
        'app.js'
    ];

    /** @var string[] */
    public $depends = [];
}
