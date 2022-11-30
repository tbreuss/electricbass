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
                <iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=musicunlimited&banner=1RDMQ3JSAHB2MQ95F7G2&f=ifr&linkID=abf0235934dae54ef61d39d960ed7184&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>
                <?php if (!empty($this->blocks['sidebar'])): ?>
                    <?= ArrayHelper::getValue($this->blocks, 'sidebar') ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 top">
            <a href="#top" class="top__link">&#x25B2; nach oben</a>
        </div>
    </div>
<?php $this->endContent(); ?>
