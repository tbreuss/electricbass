<?php
/**
 * @var \yii\web\View $this
 * @var \app\feature\alphaTab\models\AlphaTab[] $models
 */
?>
<?php $this->title = 'AlphaTab-Beispiele' ?>
<h1><?= $this->title ?></h1>

<?php foreach ($models as $model): ?>
    <h2 id="<?= $model->uid ?>"><?= $model->title ?> <span><?= $model->subtitle ?></span></h2>
    <?= app\feature\alphaTab\ListWidget::widget(['uid' => $model->uid]) ?>
<?php endforeach ?>

<?php $this->beginBlock('sidebar') ?>
    <ul class="table-of-contents">
    <?php foreach ($models as $model): ?>
        <li><a href="#<?= $model->uid ?>"><?= $model->title ?></a></li>
    <?php endforeach ?>
    </ul>
<?php $this->endBlock() ?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const links = document.querySelectorAll("a.atw");
        links.forEach(link => {
            link.setAttribute("target", "_blank");
        });
    });
</script>
