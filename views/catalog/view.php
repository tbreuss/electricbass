<?php

/**
 * @var yii\web\View $this
 * @var app\models\Catalog $model
 * @var app\components\AmazonProductDetail $amazonProductDetail
 * @var string $title
 */

use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Rating;
use app\widgets\SocialBar;
use app\helpers\Html;
use app\helpers\Url;
use yii\helpers\Markdown;

$this->title = $model->title . ' | ' . $title . ' | Katalog';
$this->params['breadcrumbs'][] = ['label' => 'Katalog', 'url' => Url::to(['catalog/overview'])];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => Url::previous('catalog' . $model->category)];
$this->params['breadcrumbs'][] = $model->title;

// PageTitel
$pageTitle = $model->title;
if (strlen($model->autor) > 0) {
    if ((strlen($pageTitle) + strlen($model->autor) <= (60 - 5 - strlen($model->productNumber)))) {
        $pageTitle .= ' von ' . $model->autor;
    }
}
if (!empty($model->productNumber)) {
    $pageTitle .= ' · ' . $model->productNumber;
}
$this->params['pageTitle'] = $pageTitle;

// Meta-Description
$metaDescription = [];
$metaDescription[] = 'Bass lernen mit ';
$metaDescription[] = '"';
$metaDescription[] = $model->title;
if(!empty($model->subtitle)) {
    $metaDescription[] = ' ';
    $metaDescription[] = $model->subtitle;
    $metaDescription[] = '"';
} else {
    $metaDescription[] = '"';
}
if(!empty($model->autor)) {
    $metaDescription[] = ' von ';
    $metaDescription[] = $model->autor;
}
if (!empty($model->productNumbers)) {
    $metaDescription[] = end($metaDescription) === '"' ? ' ' : ' · ';
    $metaDescription[] = join(' · ', $model->productNumbers);
}
$this->params['metaDescription'] = join($metaDescription);

?>

<div class="content">

    <h1><?= $model->title ?></h1>
    <?php if(!empty($model->artist)): ?>
        <p>von <?= $model->artist ?></p>
    <?php endif; ?>
    <?php if(!empty($model->subtitle)): ?>
        <p><?= $model->subtitle ?></p>
    <?php endif; ?>

    <?php if ($model->hasDefaultImage()): ?>
        <p><?= Html::img($model->getDefaultImage('@web'), ["width" => 350, "class" => "img-fluid", "alt" => $model->title . ' ' . join(' · ', $model->productNumbers)]) ?></p>
    <?php endif; ?>

    <div class="markdown"><?= Markdown::process($model->text) ?></div>

    <?php if (!empty($model->contents)): ?>
        <h3>Inhalt</h3>
        <div class="markdown"><?= Markdown::process($model->contents) ?></div>
    <?php endif; ?>

    <?php if (!empty($model->blurb)): ?>
        <h3>Klappentext</h3>
        <div class="markdown"><?= Markdown::process($model->blurb) ?></div>
    <?php endif; ?>

    <?php if ($amazonProductDetail !== null): ?>
        <?php $detailPageUrl = $amazonProductDetail->getDetailPageUrl(); ?>
        <p class="text-center"><a class="button button--big button--warning" href="<?= $detailPageUrl ?>" target="_blank">Jetzt kaufen</a></p>
    <?php elseif(!empty($model->asin)): ?>
        <p class="text-center"><a class="button button--big button--warning" href="<?= Url::toAmazonProduct($model->asin) ?>" target="_blank">Jetzt kaufen</a></p>
    <?php endif; ?>

    <?php if (!empty($model->productInfos)): ?>
        <h3>Produktinformation</h3>
        <table class="table table--small">
            <?php foreach ($model->productInfos as $info): ?>
                <tr>
                    <td class="col-md-4"><?= $info["label"] ?></td>
                    <td class="col-md-8"><?= $info["value"] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?= $this->render('//_partials/meta', [
        'categories' => [
            ['label' => 'Katalog', 'url' => ['/catalog/overview']],
            ['label' => $title, 'url' => Url::previous('catalog' . $model->category)]
        ],
        'tags' => $model->tags,
    ]); ?>

    <?= Rating::widget(["tableName" => "catalog", "tableId" => $model->id]) ?>

    <?= SocialBar::widget(["id" => $model->id, "text" => $model->title]) ?>

</div>

<?= Comments::widget(["tableName" => "catalog", "tableId" => $model->id]) ?>

<?= Hits::widget(["tableName" => "catalog", "tableId" => $model->id]) ?>

<?php if (!empty($similars)): ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title">Ähnliche Artikel</h3>
        <ul class="sidebarWidget__list">
        <?php foreach($similars AS $model): ?>
        <li class="sidebarWidget__item">
            <a class="sidebarWidget__link" href="<?= $model->url ?>">
                <strong><?= $model->title ?></strong><br>
                <span class="text-muted"><?= $model->subtitle ?></span>
            </a>
        </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->endBlock() ?>
<?php endif; ?>
