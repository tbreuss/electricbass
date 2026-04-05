<?php

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $sidebarTitle
 * @var string $pageTitle
 * @var string $metaDescription
 * @var yii\data\Pagination $pagination
 * @var string $category
 * @var string $sort
 * @var array<string, string> $filter
 * @var app\features\catalog\models\Catalog[] $models
 * @var app\features\catalog\models\Catalog[] $latest
 * @var app\features\catalog\models\Catalog[] $popular
 */

use app\features\widgets\CanonicalLink;
use app\features\widgets\LinkPager;
use app\features\widgets\ListSummary;
use app\helpers\Html;

?>

<?php
$this->title = $title . ' | Katalog';
$this->params['breadcrumbs'][] = ['label' => 'Katalog', 'url' => ['catalog/overview']];
$this->params['breadcrumbs'][] = $title;
$this->params['pageTitle'] = $pageTitle;
$this->params['metaDescription'] = $metaDescription;
CanonicalLink::widget(['isPaginated' => true, 'keepParams' => ['category']]);
?>

<div class="content">

    <h1><?= $title ?></h1>

    <?php foreach ($filter as $key => $value): ?>
        <p>Gefiltert nach <?= Yii::t('app', $key) ?>: <?= $value ?> <?= Html::a('[x]', ['catalog/index', 'category' => $category]) ?></p>
    <?php endforeach; ?>

    <?= ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>

    <div class="row cataloglist">
        <?php foreach ($models as $i => $model): ?>
            <?php $emptyClass = $model->hasDefaultImage() ? '' : 'cataloglist__link--empty' ?>
            <div class="col-6 col-sm-4 col-md-6 col-lg-4 col-xl-3 cataloglist__cell">
                <a href="<?= $model->url ?>" class="cataloglist__link <?= $emptyClass ?>">
                    <?php if ($model->hasDefaultImage()): ?>
                        <?= Html::resizeImage($model->getDefaultImage(), 290, 580, ["class" => "img-fluid cataloglist__cover", "alt" => $model->title, "loading" => "lazy"]) ?>
                    <?php else: ?>
                        <span class="img-fluid cataloglist__cover cataloglist__cover"></span>
                    <?php endif; ?>
                    <div class="cataloglist__title"><?= $model->title ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<?= $this->render('@app/features/_partials/faceted-navigation', ['applied' => $urlFragments['applied'], 'defaults' => $urlFragments['defaults']]) ?>

<?php $this->beginBlock('sidebar') ?>

<h3 class="sidebarWidget__title"><?= Html::a('Übersicht von A-Z anzeigen', ['/catalog/all', 'category' => $category]) ?></h3>

<?= $this->render('_sidebar_articles', ['title' => 'Aktuelle ' . $sidebarTitle, 'models' => $latest]) ?>

<?= $this->render('_sidebar_articles', ['title' => 'Beliebte ' . $sidebarTitle, 'models' => $popular]) ?>

<?php $this->endBlock() ?>
