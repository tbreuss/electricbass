<?php
/**
 * @var yii\web\View $this
 * @var app\feature\alphaTab\components\AlphaTabApi $alphaTab
 */
?>
<?php if ($previewImage = $alphaTab->previewImage()): ?>
    <a class="atw atw--clickable" href="<?= app\helpers\Url::to(['/alpha-tab/view', 'uid' => $alphaTab->uid()]) ?>">
        <div class="atw-play"><img src="/img/play-blue.svg" width="68" alt="Play"></div>
        <img src="<?= $previewImage ?>" alt="<?= $alphaTab->previewImageAltText() ?>" width="100%">
    </a>
<?php else: ?>
    <?php app\feature\alphaTab\WebAsset::register($this) ?>
    <?php if ($alphaTab->uid() !== null): // no indentation due to markdown parsing ?>
        <a class="atw atw--clickable" href="<?= app\helpers\Url::to(['/alpha-tab/view', 'uid' => $alphaTab->uid()]) ?>">
            <div class="atw-play"><img src="/img/play-blue.svg" width="68" alt="Play"></div>
            <div class="atw-notation" id="at<?= $alphaTab->uniqueId() ?>"><?= $alphaTab->notation() ?></div>
        </a>
    <?php else: // no indentation due to markdown parsing ?>
        <div class="atw">
            <div class="atw-notation" id="at<?= $alphaTab->uniqueId() ?>"><?= $alphaTab->notation() ?></div>
        </div>
    <?php endif ?>
    <div class="atw-attr" id="atAttr<?= $alphaTab->uniqueId() ?>"><a href="<?= app\helpers\Url::to(['/lesson/index', 'path' => '/credits']) ?>#alphatab">erstellt mit alphaTab</a></div>
    <?php if ($alphaTab->isDebug()): // no indentation due to markdown parsing ?>
        <details>
            <summary>Notation</summary>
            <pre><?= $alphaTab->notation() ?></pre>
        </details>
        <details>
            <summary>Options</summary>
            <pre><?= $alphaTab->options(['scale' => 0.9]) ?></pre>
        </details>
    <?php endif ?>
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const element = document.getElementById('at<?= $alphaTab->uniqueId() ?>');
            const api = new alphaTab.AlphaTabApi(element, <?= $alphaTab->options(['scale' => 0.9]) ?>);
            api.postRenderFinished.on(() => {
                element.style.visibility = 'visible';
                document.getElementById('atAttr<?= $alphaTab->uniqueId() ?>').style.display = 'block';
            });
        });
    </script>
<?php endif ?>
