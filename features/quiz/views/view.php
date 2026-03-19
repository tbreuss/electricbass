<?php

/**
 * @var app\features\quiz\models\Quiz $model
 * @var yii\web\View $this
 */
?>
<div>
<?php if ($model->widget === 'ReadNote'): ?>
    <?= app\features\quiz\ReadNoteWidget::widget(array_merge($model->widgetOptions, ['nextUrl' => $model->next->url ?? null])); ?>
<?php elseif ($model->widget === 'WriteNote'): ?>
    <?= app\features\quiz\WriteNoteWidget::widget($model->widgetOptions); ?>
<?php endif ?>
</div>
