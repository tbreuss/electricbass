<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\ArrayHelper;

$ads300x250 = [
    '<iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=audible&banner=02CB157YAJWVRZMZQ502&f=ifr&linkID=ffee0ebaa07884f617513efdefcc7a6c&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>',
    '<iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=musicunlimited&banner=1RDMQ3JSAHB2MQ95F7G2&f=ifr&linkID=abf0235934dae54ef61d39d960ed7184&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>',
    '<iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=amazongeneric&banner=1JA20XDVWF6Z9EDMAZ82&f=ifr&linkID=1209e7e5577f5eb7e1a5fb8eea0a1b5e&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>',
    '<iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=prime_video&banner=08GX2R33ZHTH7HJXRHR2&f=ifr&linkID=dc242073bd6ee9a5384eab288dbedb93&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>',
    '<iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=channels&banner=11TSQ2CDN93P3KZMH802&f=ifr&linkID=84ad385760aa2ee87eafa5d75a5c9ad2&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>',
    '<iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=3&p=12&l=ur1&category=amazonkids&banner=1XAZYM9BQ761EBNK9Z82&f=ifr&linkID=2380146fca7c46df48570de4c5fb2987&t=electricbas03-21&tracking_id=electricbas03-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0" sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation-by-user-activation"></iframe>',
];

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
                <div style="margin-bottom:1rem"><?= $ads300x250[rand(0, count($ads300x250) - 1)] ?></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 top">
            <a href="#top" class="top__link">&#x25B2; nach oben</a>
        </div>
    </div>
<?php $this->endContent(); ?>
