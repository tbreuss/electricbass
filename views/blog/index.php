<?php
/**
 * @var yii\data\Pagination $pagination
 * @var yii\web\View $this
 * @var string $sort
 * @var app\models\Blog[] $blogs
 * @var app\models\Blog[] $latest
 * @var app\models\Blog[] $popular
 */

use app\widgets\ListSummary;
use app\widgets\ListView;
use app\widgets\LinkPager;

$this->title = 'Blog';
$this->params['breadcrumbs'][] = 'Blog';
$this->params['pageTitle'] = sprintf('Blog für Bassist*innen zum Thema E-Bass und Musik (%d/%d)', $pagination->page+1, $pagination->pageCount);
$this->params['metaDescription'] = sprintf('Blog zum Thema E-Bass und Musik, mit inspirierenden und lesenswerten Artikeln aus der Welt der tiefen Töne (Seite %d von %d)', $pagination->page+1, $pagination->pageCount);

?>

<div class="content">

    <h1>Blog für E-Bass und Bassisten</h1>

    <?= ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>

    <?= ListView::widget(["ratingStyle" => "none", "ratingContext" => "blog", "models" => $blogs]) ?>

</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<?php $this->beginBlock('sidebar') ?>

    <?php if (!empty($latest)): ?>
        <div class="sidebarWidget">
            <h3 class="sidebarWidget__title">Aktuelle Blogposts</h3>
            <ul class="sidebarWidget__list">
            <?php foreach($latest AS $model): ?>
                <li class="sidebarWidget__item">
                    <a class="sidebarWidget__link" href="<?= $model->url ?>">
                        <strong><?= $model->title ?></strong><br>
                        <span class="text-muted"><?= Yii::$app->formatter->asDate($model->modified, 'long') ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($popular)): ?>
        <div class="sidebarWidget">
            <h3 class="sidebarWidget__title">Beliebte Blogposts</h3>
            <ul class="sidebarWidget__list">
            <?php foreach($popular AS $model): ?>
                <li class="sidebarWidget__item">
                    <a class="sidebarWidget__link" href="<?= $model->url ?>">
                        <strong><?= $model->title ?></strong><br>
                        <span class="text-muted"><?= Yii::$app->formatter->asDate($model->modified, 'long') ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

<?php $this->endBlock() ?>
