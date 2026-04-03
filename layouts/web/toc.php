<?php /** @var yii\web\View $this */ ?>
<?php /** @var string $content */ ?>
<?php $this->beginContent('@app/layouts/web/base.php'); ?>
<div class="row">
    <div class="col-12 d-block d-md-none">
        <h1><?= app\helpers\Html::encode($this->blocks['title']) ?></h1>
    </div>
    <div class="col-md-8 content-wrap order-2 order-md-1">
        <h1 class="d-none d-md-block"><?= app\helpers\Html::encode($this->blocks['title']) ?></h1>
        <?= $content ?>
    </div>
    <div class="col-md-4 content-wrap order-1 order-md-2 sidebar">
        <div class="sidebar__inner"><?= $this->blocks['tableOfContents'] ?></div>
    </div>
</div>
<?php $this->endContent(); ?>
