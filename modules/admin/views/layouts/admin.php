<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\bootstrap\BootstrapPluginAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

BootstrapPluginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | Admin</title>
    <?php $this->head() ?>
    <style>
        div.grid-view table td:last-child {
            white-space:nowrap;
        }
        table.detail-view th {
            white-space:nowrap;
        }
    </style>
</head>
<body class="<?= Yii::$app->controller->id ?> <?= Yii::$app->controller->id ?>-<?= isset(Yii::$app->controller->action) ? Yii::$app->controller->action->id : 'nil' ?>" itemscope itemtype="http://schema.org/WebPage">
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => 'ADMIN',
    'brandUrl' => ['/admin'],
    'options' => [
        'class' => 'navbar-default',
    ],
    'innerContainerOptions' => [
        'class' => 'container-fluid'
    ]
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        ['label' => 'Blog', 'url' => ['/admin/blog']],
        ['label' => 'Lektionen', 'url' => ['/admin/lesson']],
        ['label' => 'Websites', 'url' => ['/admin/website']],
        ['label' => 'Videos', 'url' => ['/admin/video']],
        Yii::$app->user->isGuest ? (
        ['label' => 'Login', 'url' => ['/admin/default/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/admin/default/logout'], 'post', ['style' => 'margin-top:8px'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>'
        )
    ],
]);
NavBar::end();
?>
<div class="container-fluid">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
<script>
  // see https://stackoverflow.com/questions/454202/creating-a-textarea-with-auto-resize
  const tx = document.getElementsByTagName('textarea');
  for (let i = 0; i < tx.length; i++) {
    tx[i].setAttribute('style', 'height:' + (tx[i].scrollHeight) + 'px;overflow-y:hidden;');
  }
</script>
</body>
</html>
<?php $this->endPage() ?>
