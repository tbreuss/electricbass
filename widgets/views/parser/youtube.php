<?php
use yii\helpers\Markdown;
use yii\widgets\Spaceless;

?>

<?php #$this->registerCssFile("@web/css/video.css") ?>

<?php Spaceless::begin() ?>
<div class="shortcode shortcode--video">

    <?php if (!empty($title)): ?>
        <h3><?php echo $title ?></h3>
    <?php endif; ?>

    <?php /* https://getbootstrap.com/docs/5.0/helpers/ratio/ */ ?>
    <div class="ratio ratio--16x9">
        <iframe src="https://www.youtube.com/embed/<?php echo $key ?>?rel=0" frameborder="0" allowfullscreen></iframe>
    </div>

    <?php if (empty($text)): ?>
        <div style="margin-top:0.7em">
            <?php echo Markdown::process($text) ?>
        </div>
    <?php endif; ?>

</div>
<?php Spaceless::end() ?>
