<?php
use yii\helpers\ArrayHelper;
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <?= $content ?>
    <?= ArrayHelper::getValue($this->blocks, 'comments') ?>
    <div class="top">
        <a href="#" class="top__link">&#x25B2; nach oben</a>
    </div>
<?php $this->endContent(); ?>
