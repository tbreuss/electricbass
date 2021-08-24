<?php

use app\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\Spaceless;

?>

<?php if (empty($models)) {
    return;
} ?>

<?php Spaceless::begin() ?>
<div class="shortcode shortcode--websites">
<?php foreach ($models as $model) : ?>
    <h4><?= Html::a($model->title, $model->url) ?></h4>
    <?php if (!empty($model->abstract)) : ?>
        <p><?= strip_tags(Markdown::process($model->abstract)) ?></p>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php Spaceless::end() ?>