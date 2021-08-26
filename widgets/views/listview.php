<?php

/**
 * @var string $ratingContext
 * @var string $ratingStyle
 * @var app\models\Blog[]|app\models\Lesson[] $models
 */

use app\helpers\Html;
use app\widgets\RatingReadOnly;
use yii\helpers\Markdown;

?>

<?php /* Siehe http://www.bootply.com/KYX5whhXoh */ ?>
<div class="widget widget-listview">
    <?php foreach ($models as $i => $model): ?>
        <?php if ($i > 0) {
            echo "<hr>";
        } ?>

        <div class="row">
            <?php if ($model->hasDefaultImage()): ?>
                <div class="col-sm-3">
                    <a href="<?= $model->url ?>">
                        <?= Html::resizeImage($model->getDefaultImage(), 290, 580, ["class" => "img-fluid", "alt" => $model->title]) ?>
                    </a>
                </div>
                <div class="col-sm-9">
                    <h3 class="title"><a href="<?= $model->url ?>"><?= $model->title ?></a></h3>
                    <?php /*<p class="text-muted"><span class="glyphicon glyphicon-lock"></span> Available Exclusively for Premium
                        Members</p>*/ ?>
                    <?= RatingReadOnly::widget(["style" => $ratingStyle, "tableName" => $ratingContext, "tableId" => $model->id]) ?>
                    <?php /*if (!empty($model->modified)): ?>
                        <p class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDate($model->modified, 'long') ?></p>
                    <?php endif;*/ ?>
                    <p><?= strip_tags(Markdown::process($model->abstract)) ?></p>
                    <?php /*<p class="text-muted">Presented by <a href="#">Ellen Richey</a></p>*/ ?>
                </div>
            <?php else: ?>
                <div class="col-sm-12">
                    <h3 class="title"><a href="<?= $model->url ?>"><?= $model->title ?></a></h3>
                    <?= RatingReadOnly::widget(["style" => $ratingStyle, "tableName" => $ratingContext, "tableId" => $model->id]) ?>
                    <?php /*if (!empty($model->modified)): ?>
                        <p class="text-muted"><span class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDate($model->modified, 'long') ?></p>
                    <?php endif;*/ ?>
                    <p><?= strip_tags(Markdown::process($model->abstract)) ?></p>
                    <?php /*<p class="text-muted">Presented by <a href="#">Ellen Richey</a></p>*/ ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
