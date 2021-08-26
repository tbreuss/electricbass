<?php

/**
 * @var string $sort
 * @var app\models\Website[] $latest
 * @var app\models\Website[] $popular
 * @var app\models\Website[] $websites
 * @var yii\data\Pagination $pagination
 * @var yii\web\View $this
 */

use app\helpers\Html;
use app\widgets\ListSummary;
use app\widgets\LinkPager;

$this->title = 'Websites';
$this->params['breadcrumbs'][] = 'Websites';
$this->params['pageTitle'] = sprintf('Websites zum Thema E-Bass (%d/%d)', $pagination->page + 1, $pagination->pageCount);
$this->params['metaDescription'] = sprintf('Umfangreicher Katalog mit Marken und Herstellern von E-Bässen und Zubehör, Blogs von Bassist*innen und Websites zum Thema E-Bass. (Seite %d von %d)', $pagination->page + 1, $pagination->pageCount);

?>

<div class="content">
    <h1>Websites zum Thema E-Bass</h1>
    <?= ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>

    <div class="row websites">
    <?php foreach ($websites as $model): ?>
        <div class="col-6 col-sm-4 col-md-6 col-lg-4 websites__cell">
            <a href="<?= $model->url ?>" class="websites__link">
                <?php if ($model->hasDefaultImage()): ?>
                    <?= Html::cachedCropedImage($model->getDefaultImage(), 400, 300, ["class" => "img-fluid websites__cover", "alt" => $model->title]) ?>
                <?php else: ?>
                    <?= Html::cachedCropedImage('/img/bg.png', 400, 300, ["class" => "img-fluid websites__cover", "alt" => $model->title]) ?>
                <?php endif; ?>
                <div class="websites__title"><?= $model->title ?></div>
            </a>
        </div>
    <?php endforeach; ?>
    </div>

</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<?php $this->beginBlock('sidebar') ?>

<h3 class="sidebarWidget__title"><?= Html::a('Übersicht von A-Z anzeigen', ['/website/all']) ?></h3>

<?= $this->render('_articles', ['title' => 'Aktuelle Websites', 'models' => $latest]) ?>

<?= $this->render('_articles', ['title' => 'Beliebte Websites', 'models' => $popular]) ?>

<?php $this->endBlock() ?>
