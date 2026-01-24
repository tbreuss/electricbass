<?php
/**
 * @var \yii\web\View $this
 * @var \app\feature\alphaTab\components\AlphaTabApi $alphaTab
 */
?>
<?php \app\feature\alphaTab\WebAsset::register($this) ?>
<style>
    .alpha-tab {
        position: relative;
    }
    .alpha-tab--clickable .at-surface * {
        cursor: pointer;
    }
    .alpha-tab-play {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 1000;
        width: 68px;
        height: 48px;
        margin-left: -34px;
        margin-top: -24px;
        display: none;
    }
    .alpha-tab:hover .alpha-tab-play {
        display: block;
    }
    .alpha-tab__notation {
        visibility: hidden;
    }
    .at-surface div:last-of-type {
        display: none !important;
    }
</style>

<?php if ($alphaTab->uid() !== null): ?>
    <div class="alpha-tab alpha-tab--clickable" onclick="window.location='<?= app\helpers\Url::to(['/alpha-tab/view', 'uid' => $alphaTab->uid()]) ?>';">
        <a class="alpha-tab-play" href="<?= app\helpers\Url::to(['/alpha-tab/view', 'uid' => $alphaTab->uid()]) ?>"><img src="/img/play-blue.svg" width="68" alt="Play"></a>
        <div class="alpha-tab__notation" id="alphaTab<?= $alphaTab->uniqueId() ?>"><?= $alphaTab->notation() ?></div>
    </div>
<?php else: ?>
    <div class="alpha-tab">
        <div class="alpha-tab__notation" id="alphaTab<?= $alphaTab->uniqueId() ?>"><?= $alphaTab->notation() ?></div>
    </div>
<?php endif ?>

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
