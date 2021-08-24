<?php

/**
 * @var array<int, array>$gallery
 */

use app\helpers\Html;
use yii\widgets\Spaceless;

?>

<?php if (is_array($gallery) && !empty($gallery)) :
    ?><?php Spaceless::begin(); ?>
    <div class="shortcode shortcode--gallery">

        <?php if (!empty($title)) : ?>
            <h2><?php echo $title ?></h2>
        <?php endif; ?>

        <ul>
            <?php foreach ($gallery as $i => $image) : ?>
                <li class="image-<?= $i ?>"><?php echo Html::img("@web/" . $image["url"], ["alt" => "", "class" => "img-fluid"]) ?></a></li>
            <?php endforeach; ?>
        </ul>

    </div>
    <?php Spaceless::end(); ?><?php
endif; ?>
