<?php
/**
 * @var View $this
 * @var string $content
 */

use yii\helpers\ArrayHelper;
use yii\web\View;

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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 top">
            <a href="#" class="top__link">&#x25B2; nach oben</a>
        </div>
    </div>
<?php $this->endContent(); ?>
