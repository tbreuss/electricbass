<?php
use app\helpers\Html;
use yii\widgets\Spaceless;

?>

<?php Spaceless::begin(); ?>

    <div class="shortcode shortcode--image">

        <?php if(!empty($title)): ?>
            <h2><?php echo $title ?></h2>
        <?php endif; ?>

        <div class="image">
            <?php
                $options = ["class" => "img-fluid", "alt" => ""];
                if ($width > 0) {
                    $options["width"] = $width;
                }
                if ($height > 0) {
                    $options["height"] = $height;
                }
                if (strlen($alt) > 0) {
                    $options["alt"] = $alt;
                }
                echo Html::img("@web/" . $url, $options);
            ?>
        </div>

        <?php if(!empty($copyrightLabel)): ?>
            <div class="copyright">Copyright:
                <?php if(empty($copyrightUrl)): ?>
                    <?php echo $copyrightLabel ?>
                <?php else: ?>
                    <a target="_blank" href="<?php echo $copyrightUrl ?>"><?php echo $copyrightLabel ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($caption)): ?>
            <div class="caption"><?php echo $caption ?></div>
        <?php endif; ?>

    </div>

<?php Spaceless::end(); ?>
