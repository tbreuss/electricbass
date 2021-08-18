<?php
/**
 * @var Pagination $pagination
 * @var View $this
 * @var array<string, string> $filter
 * @var string $sort
 * @var Album[] $models
 * @var Album[] $latest
 * @var Album[] $popular
 */

use app\helpers\Html;
use app\helpers\Url;
use app\models\Album;
use app\widgets\ListSummary;
use app\widgets\LinkPager;
use app\widgets\RatingReadOnly;
use yii\data\Pagination;
use yii\web\View;

?>

<?php
$this->title = 'Bass-Alben | Katalog';
$this->params['breadcrumbs'][] = ['label' => 'Katalog', 'url' => Url::to(['catalog/overview'])];
$this->params['breadcrumbs'][] = 'Bass-Alben';

$this->params['pageTitle'] = sprintf('Inspirierende Musikalben von E-Bassist*innen (%d/%d)', $pagination->page+1, $pagination->pageCount);
$this->params['metaDescription'] = sprintf('Inspirierende und hörenswerte Musikalben berühmter E-Bassisten. Unentdeckte Alben und Songs von Bassisten wie du und ich (Seite %d von %d)', $pagination->page+1, $pagination->pageCount);

?>

<div class="content">

    <h1>Musikalben von E-Bassisten</h1>

    <?php foreach ($filter as $key => $value): ?>
        <p>Gefiltert nach <?= Yii::t('app', $key) ?>:
            <?= $value ?> <?= Html::a('[x]', ['album/index']) ?>
        </p>
    <?php endforeach; ?>

    <?= ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>

    <div class="row albumlist">
        <?php foreach ($models as $i => $model): ?>
            <div class="col-6 col-sm-4 col-md-6 col-lg-4 albumlist__cell">
                <a href="<?= $model->url ?>" class="albumlist__link">
                    <?php if ($model->hasDefaultImage()): ?>
                        <?= Html::resizeImage($model->getDefaultImage(), 290, 580, ["class" => "img-fluid albumlist__cover", "alt" => $model->fullTitle]) ?>
                    <?php else: ?>
                        <?= Html::img('/img/bg.png', ["width" => 290, "height" => 290, "class" => "img-fluid albumlist__cover", "alt" => $model->fullTitle]) ?>
                    <?php endif; ?>
                    <?= RatingReadOnly::widget(["style" => "none", "tableName" => "album", "tableId" => $model->id]) ?>
                    <div class="albumlist__title"><?= $model->title ?></div>
                    <div class="albumlist__artist"><?= $model->artist ?></div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?= LinkPager::widget(['pagination' => $pagination]) ?>

<?php $this->beginBlock('sidebar') ?>

<h3 class="sidebarWidget__title"><?= Html::a('Übersicht von A-Z anzeigen', ['/album/all']) ?></h3>

<?= $this->render('_articles', ['title' => 'Aktuelle Musikalben', 'models' => $latest]) ?>

<?= $this->render('_articles', ['title' => 'Beliebte Musikalben', 'models' => $popular]) ?>

<?php $this->endBlock() ?>
