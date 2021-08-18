<?php
/**
 * @var View $this
 * @var string $content
 */

use yii\helpers\ArrayHelper;
use yii\web\View;

?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <?= $content ?>
    <?= ArrayHelper::getValue($this->blocks, 'comments') ?>
    <div class="top">
        <a href="#" class="top__link">&#x25B2; nach oben</a>
    </div>
<?php $this->endContent(); ?>
