<?php

/**
 * @var int $id
 * @var string $uid
 * @var string $content
 */

?>
<div class="shortcode shortcode--alpha-tab">
    <?= app\features\alphaTab\ListWidget::widget(['id' => $id, 'uid' => $uid, 'content' => $content]) ?>
</div>
