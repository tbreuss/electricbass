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
            <div class="top">
                <a href="#" class="top__link">&#x25B2; nach oben</a>
            </div>
        </div>
        <div class="col-md-4 sidebar">
            <div class="sidebar__inner">
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>
