<?php
/**
 * @var string $title
 * @var string[] $urls
 * @var string[] $labels
 * @var string[] $description
 */
?>
<?php if (count($urls) === 0) {
    return;
} ?>

<?php yii\widgets\Spaceless::begin(); ?>

<div class="shortcode shortcode--links">

    <?php if (!empty($title)): ?>
        <h2><?php echo $title ?></h2>
    <?php endif; ?>

    <ul>
        <?php foreach ($urls as $i => $url): ?>
            <?php if (!empty($url)): ?>
                <li>
                    <?php $label = !empty($labels[$i]) ? $labels[$i] : $url ?>
                    <?php $description = isset($descriptions[$i]) ? $descriptions[$i] : '' ?>

                    <?php if (substr($url, 0, 7) == 'http://'): ?>
                        <?php $label = str_replace('http://', '', $label) ?>
                        <a target="_blank" href="<?php echo ($url) ?>"><?php echo $label ?></a>
                    <?php elseif (strpos($url, '@')): ?>
                        <a href="mailto:<?php echo $url ?>"><?php echo $label ?></a>
                    <?php elseif (is_file($url)): ?>
                        <a href="<?php echo app\helpers\Url::base(true) ?>/<?php echo $url ?>"><?php echo $label ?></a>
                    <?php endif; ?>
                    <?php if (!empty($description)): ?>
                        <?php echo $description ?>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

</div>
<?php yii\widgets\Spaceless::end(); ?>
