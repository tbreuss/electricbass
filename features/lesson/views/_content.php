<?php
/**
 * @var yii\web\View $this
 * @var app\features\lesson\models\Lesson $model
 * @var app\features\lesson\models\Lesson[] $similars
 * @var app\features\lesson\models\Lesson[] $latest
 * @var array $breadcrumbs
 * @var string $title
 * @var string $content
 * @phpstan-var array<int, array{"label": string, "url": string}> $breadcrumbs
 */
?>
<div class="content">
    <?php if (!empty($model->text)): ?>
        <div class="widget widget-parser"><?= $content ?></div>
        <?php if ($model->hasChanges()): ?>
            <div class="changes">
                <h3 class="changes__title">Änderungen</h3>
                <ul class="changes__list">
                    <?php foreach ($model->getChanges() as $change): ?>
                        <li class="changes__listItem"><?= $change['date'] ?> <?= $change['text'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?= $this->render('//_partials/meta', [
            'categories' => $breadcrumbs,
            'tags' => $model->tags,
        ]); ?>
    <?php endif; ?>
</div>

<?php if (!empty($model->text)): ?>
    <?= app\features\rating\RatingShare::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>
<?php else: ?>
    <?= app\features\rating\RatingReadOnly::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>
<?php endif; ?>

<?= app\features\comment\Widget::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>

<?= app\widgets\Hits::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>
