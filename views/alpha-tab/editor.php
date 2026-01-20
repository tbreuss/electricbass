<?php
/**
 * @var yii\web\View $this
 * @var \app\components\AlphaTabApi $alphaTab
 * @var string $instrument
 * @var string $notation
 */
?>

<?php $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']) ?>
<?php app\assets\AlphaTabAsset::register($this) ?>

<div class="alpha-tab">
    <?php if ($alphaTab->uid() !== null): ?>
        <a href="<?= app\helpers\Url::to(['/alpha-tab/view', 'uid' => $alphaTab->uid()]) ?>"><div class="alpha-tab__notation" id="alphaTab<?= $alphaTab->uniqueId() ?>"><?= $alphaTab->notation() ?></div></a>
    <?php else: ?>
        <div class="alpha-tab__notation" id="alphaTab<?= $alphaTab->uniqueId() ?>"><?= $alphaTab->notation() ?></div>
    <?php endif ?>
</div>

<?php if ($alphaTab->isDebug()): ?>
    <details>
        <summary>Notation</summary>
        <pre><?= $alphaTab->notation() ?></pre>
    </details>
    <details>
        <summary>Options</summary>
        <pre><?= $alphaTab->options() ?></pre>
    </details>
<?php endif ?>

<script>
    window.addEventListener("DOMContentLoaded", () => {
        const element = document.getElementById('alphaTab<?= $alphaTab->uniqueId() ?>');
        const api = new alphaTab.AlphaTabApi(element, <?= $alphaTab->options() ?>);
        api.postRenderFinished.on(() => {
            element.style.visibility = 'visible';
        });
    });
</script>

<?= app\helpers\Html::beginForm() ?>
<?= app\helpers\Html::dropDownList('instrument', $instrument, ['NONE' => '', 'FOUR_STRING_BASS' => 'Bass 4-String']) ?>
<?= app\helpers\Html::textarea('content', $notation, ['style' => 'width: 100%; height: 10rem']) ?>
<?= app\helpers\Html::submitButton('Do') ?>
<?= app\helpers\Html::endForm();
