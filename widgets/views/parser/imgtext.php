<?php

/**
 * @var string $position
 * @var string $text
 * @var string $title
 * @var string $url
 */
use app\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\Spaceless;

?>

<?php Spaceless::begin(); ?>

    <?php
        $divWidth = 620;
        $width = 620;
        $float = '';
        $clearer = '';
        $class = "";
    ?>
    <?php if (in_array($position, array('top','bottom'))): ?>
        <?php $divWidth = '100%';
        $class = "img-fluid"; ?>
    <?php elseif (in_array($position, array('left','right'))): ?>
        <?php $width = 300 ?>
        <?php $float = ($position == 'left') ? 'float:left;margin:0 20px 1em 0' : 'float:right;margin:0 0 1em 20px' ?>
        <?php $clearer = '<div class="clear"></div>' ?>
    <?php endif; ?>

    <div class="shortcode shortcode--imgtext">

        <?php if (!empty($title)): ?>
            <h2><?= $title ?></h2>
        <?php endif; ?>

        <?php if ($position == 'bottom'): ?>
            <div class="text"><?= Markdown::process($text) ?></div>
        <?php endif; ?>

        <div class="imgtext" style="max-width:<?= $width ?>px;<?= $float ?>">
            <?= Html::img("@web/" . $url, ["width" => $width, "alt" => $title, "class" => $class]) ?>
            <?php if (!empty($copyrightLabel)): ?>
                <div class="copyright">Copyright:
                    <?php if (empty($copyrightUrl)): ?>
                        <?= $copyrightLabel ?>
                    <?php else: ?>
                        <a target="_blank" href="<?= $copyrightUrl ?>"><?= $copyrightLabel ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($caption)): ?>
                <div class="caption"><?= $caption ?></div>
            <?php endif; ?>
        </div>

        <?php if (in_array($position, array('left','right'))): ?>
            <div class="text"><?= Markdown::process($text) ?></div>
        <?php endif; ?>

        <?= $clearer ?>

        <?php if ($position == 'top'): ?>
            <div class="text"><?= Markdown::process($text) ?></div>
        <?php endif; ?>

    </div>

<?php Spaceless::end(); ?>
