<?php

namespace app\features\quiz;

use yii\web\AssetBundle;

final class Asset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@app/features/quiz/assets';

    /** @var string */
    public $baseUrl = '@web';

    /** @var string[] */
    public $css = [
        'styles.css',
    ];

    /** @var string[] */
    public $js = [];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}
