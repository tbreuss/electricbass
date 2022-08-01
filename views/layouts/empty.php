<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\ArrayHelper;

?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <?= $content ?>
    <?= ArrayHelper::getValue($this->blocks, 'comments') ?>
    <div class="top">
        <a href="#top" class="top__link">&#x25B2; nach oben</a>
    </div>
<?php $this->endContent(); ?>
