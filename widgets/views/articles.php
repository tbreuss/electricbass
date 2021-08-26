<?php
/**
 * @var ?string $title
 * @var app\models\Search[] $models
 */
?>
<div class="widget widget-advertisement">
    <div class="panel panel-default">
        <?php if (!is_null($title)): ?>
        <div class="panel-heading">
            <h4 class="panel-title"><?= $title ?></h4>
        </div>
        <?php endif; ?>
        <div class="list-group">
            <?php foreach ($models as $model): ?>
                <a class="list-group-item" href="<?= $model->url ?>">
                    <p class="list-group-item-text">
                        <strong><?= $model->title ?></strong><br>
                        <?= Yii::$app->formatter->asDate($model->created, 'medium') ?> /
                        <?= $model->getContextText() ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
