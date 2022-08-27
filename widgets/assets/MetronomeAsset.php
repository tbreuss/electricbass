<?php

namespace app\widgets\assets;

use yii\web\AssetBundle;

final class MetronomeAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/widgets/assets/metronome';

    /** @var string */
    public $baseUrl = '@web';

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
