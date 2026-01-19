<?php
/**
 * @var \yii\web\View $this
 * @var ?string $uid
 * @var string $notation
 * @var string $options
 * @var string $uniqid
 * @var bool $isDebug
 */
?>
<?php app\assets\AlphaTabAsset::register($this) ?>
<style>
    .alpha-tab__notation { visibility: hidden; }
    .at-surface div:last-of-type { display: none !important; }
</style>
<div class="alpha-tab">
    <a href="<?= app\helpers\Url::to(['/alpha-tab/view', 'uid' => $uid]) ?>"><div class="alpha-tab__notation" id="alphaTab<?= $uniqid ?>"><?= $notation ?></div></a>
</div>
<?php if ($isDebug): ?>
    <details>
        <summary>Notation</summary>
        <pre><?= $notation ?></pre>
    </details>
    <details>
        <summary>Options</summary>
        <pre><?= $options ?></pre>
    </details>
<?php endif ?>
<script>
    window.addEventListener("DOMContentLoaded", () => {
        const element = document.getElementById('alphaTab<?= $uniqid ?>');
        const api = new alphaTab.AlphaTabApi(element, <?= $options ?>);
        api.postRenderFinished.on(() => {
            element.style.visibility = 'visible';
        });
    });
</script>
