<?php
use yii\widgets\Spaceless;

?>

<?php Spaceless::begin() ?>
<div class="shortcode shortcode--video">
    <div class="ratio ratio--16x9">
        <iframe src="https://player.vimeo.com/video/<?php echo $key ?>?color=ffffff" width="<?php echo $width ?>" height="<?php echo $height ?>" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
    </div>
</div>
<?php Spaceless::end() ?>
