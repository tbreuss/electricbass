<?php
/** @var yii\web\View $this */ ?>
<?php /** @var string $content */ ?>
<?php $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']) ?>
<?= app\widgets\AlphaTab::widget(['content' => $content]) ?>
<?= app\helpers\Html::beginForm() ?>
<?= app\helpers\Html::textarea('content', $content, ['style' => 'width: 100%; height: 10rem']) ?>
<?= app\helpers\Html::submitButton('Do') ?>
<?= app\helpers\Html::endForm();
