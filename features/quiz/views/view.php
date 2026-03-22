<?php

/**
 * @var app\features\quiz\models\Quiz $model
 * @var yii\web\View $this
 */
?>
<?php $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']) ?>
<?php $quizAsset = app\features\quiz\Asset::register($this) ?>
<?php if ($model->widget === 'Markdown'): ?>
    <div class="quiz">
        <div class="quiz-header">
            <img onclick="window.open('<?= app\helpers\Url::to($model->categoryUrl) ?>', '_self');" class="quiz-header-close" src="<?= $quizAsset->baseUrl ?>/x-circle.svg" width="24" height="24" alt="Schliessen">
            <div class="quiz-header-progress">
                <div style="background-color: rgb(114, 172, 81);">&nbsp;</div>
            </div>
        </div>
        <div class="quiz-result">
            <h1 class="quiz-title"><?= $model->title ?></h1>
            <div><?= app\widgets\Parser::widget(["model" => $model, "attribute" => "content"]) ?></div>
            <button class="quiz-result-next-button" onclick="window.open('<?= app\helpers\Url::to('/quiz/' . $model->nextUid) ?>', '_self');">Nächste Übung</button>
        </div>
    </div>
<?php elseif ($model->widget === 'ReadNote'): ?>
    <?php $options = ['lead' => $model->lead, 'hint' => $model->hint, 'categoryUrl' => $model->categoryUrl, 'nextQuizUid' => $model->nextUid ?? null] ?>
    <div><?= app\features\quiz\ReadNoteWidget::widget(array_merge($model->widgetOptions, $options)); ?></div>
<?php elseif ($model->widget === 'WriteNote'): ?>
    <div><?= app\features\quiz\WriteNoteWidget::widget($model->widgetOptions); ?></div>
<?php endif ?>
