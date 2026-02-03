<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\ArrayHelper;

?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
    <div class="row">
        <div class="col-md-8 content-wrap">
            <?= $content ?>
            <?= ArrayHelper::getValue($this->blocks, 'comments') ?>
        </div>
        <div class="col-md-4 sidebar">
            <div class="sidebar__inner">
                <?php if (!empty($this->blocks['sidebar'])): ?>
                    <?= ArrayHelper::getValue($this->blocks, 'sidebar') ?>
                <?php endif; ?>
                <div style="margin-bottom:1rem"><?= app\widgets\Banner::widget() ?></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 top">
            <a href="#top" class="top__link">&#x25B2; nach oben</a>
        </div>
    </div>
<?php $this->endContent(); ?>
