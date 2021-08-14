<?php
    use app\helpers\Html;
    use yii\widgets\Spaceless;
?>

<?php if(empty($urls)) return; ?>

<?php Spaceless::begin(); ?>
<?php $urls = empty($urls) ? array() : explode("\n", $urls) ?>
<?php $labels = empty($labels) ? array() : explode("\n", $labels) ?>
<?php $descriptions = empty($descriptions) ? array() : explode("\n", $descriptions) ?>

<div class="shortcode shortcode--links">

    <?php if(!empty($title)): ?>
        <h2><?php echo $title ?></h2>
    <?php endif; ?>

    <ul>
        <?php foreach($urls AS $i=>$url): ?>
            <?php if(!empty($url)) : ?>
                <li>
                    <?php $label = !empty($labels[$i]) ? $labels[$i] : $url ?>
                    <?php $description = isset($descriptions[$i]) ? $descriptions[$i] : '' ?>

                    <?php if(substr($url,0,7)=='http://'): ?>
                        <?php $label = str_replace('http://', '', $label) ?>
                        <a target="_blank" href="<?php echo ($url) ?>"><?php echo $label ?></a>
                    <?php elseif(strpos($url,'@')): ?>
                        <a href="mailto:<?php echo $url ?>"><?php echo $label ?></a>
                    <?php elseif(is_file($url)): ?>
                        <a href="<?php echo baseUrl() ?>/<?php echo $url ?>"><?php echo $label ?></a>
                    <?php endif; ?>
                    <?php if(!empty($description)): ?>
                        <?php echo $description ?>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

</div>
<?php Spaceless::end(); ?>