<?php

/**
 * @var yii\data\Pagination $pagination
 * @var yii\web\View $this
 * @var array<string, string> $filter
 * @var string $sort
 * @var app\features\album\models\Album[] $models
 * @var app\features\album\models\Album[] $latest
 * @var app\features\album\models\Album[] $popular
 */

use app\features\rating\RatingReadOnly;
use app\features\widgets\CanonicalLink;
use app\features\widgets\LinkPager;
use app\features\widgets\ListSummary;
use app\helpers\Html;
use app\helpers\Url;

?>

<?php
$this->title = 'Bass-Alben | Katalog';
$this->params['breadcrumbs'][] = ['label' => 'Katalog', 'url' => Url::to(['catalog/overview'])];
$this->params['breadcrumbs'][] = 'Bass-Alben';

$this->params['pageTitle'] = sprintf('Inspirierende Musikalben von E-Bassist*innen (%d/%d)', $pagination->page + 1, $pagination->pageCount);
$this->params['metaDescription'] = sprintf('Inspirierende und hörenswerte Musikalben berühmter E-Bassisten. Unentdeckte Alben und Songs von Bassisten wie du und ich (Seite %d von %d)', $pagination->page + 1, $pagination->pageCount);
CanonicalLink::widget(['isPaginated' => true]);
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
                        <?= Html::resizeImage($model->getDefaultImage(), 290, 580, ["class" => "img-fluid albumlist__cover", "alt" => $model->fullTitle, "loading" => "lazy"]) ?>
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

<script>
    let page = 0;
    let sort = '-modified';

    function inputChanged(newPage = null, newSort = null) {
        page = newPage ?? page;
        sort = newSort ?? sort;
        window.location.href = '#page=' + page + '&sort=' + sort;
    }

    function sendForm(hash) {
        const params = new URLSearchParams(hash);
        if (!(params.has('page') && params.has('sort'))) {
            return;
        }

        // Create a form
        const form = document.createElement("form");
        form.method = "POST";

        const csrfInput = document.createElement("input");
        csrfInput.type = "text";
        csrfInput.name = "_csrf";
        csrfInput.value = document.head.querySelector("[name~=csrf-token][content]").content;
        form.appendChild(csrfInput);

        const pageInput = document.createElement("input");
        pageInput.type = "text";
        pageInput.name = "page";
        pageInput.value = params.get(pageInput.name);
        form.appendChild(pageInput);

        const sortInput = document.createElement("input");
        sortInput.type = "text";
        sortInput.name = "sort";
        sortInput.value = params.get(sortInput.name);
        form.appendChild(sortInput);

        document.body.appendChild(form);

        form.submit();
    }

    window.addEventListener("hashchange", (event) => {
        event.preventDefault();
        const hashPart = event.newURL.split('#');
        if (hashPart[1]) {
            sendForm(hashPart[1]);
        }
    });

    const loadedHash = '#page=<?= $currentPage ?>&sort=<?= $currentSort ?>';
    if ((location.hash !== '') && (loadedHash !== location.hash)) {
        sendForm(location.hash.substring(1));
    }
</script>
