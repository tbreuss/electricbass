<?php

/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $sidebarTitle
 * @var app\entities\AtoZGroupedEntries[] $groupedEntries
 * @var string $category
 * @var app\models\Catalog[] $latest
 * @var app\models\Catalog[] $popular
 */

use app\helpers\Html;

?>
<div class="content catalog-all">
    <h1><?= $title ?></h1>
    <div class="row">
        <?= $this->render('/_partials/grouped_entries', ['groupedEntries' => $groupedEntries]) ?>
    </div>
</div>

<?php $this->beginBlock('sidebar') ?>

<h3 class="sidebarWidget__title"><?= Html::a('Normale Ansicht anzeigen', ['/catalog/index', 'category' => $category]) ?></h3>

<?= $this->render('_sidebar_articles', ['title' => 'Aktuelle ' . $sidebarTitle, 'models' => $latest]) ?>

<?= $this->render('_sidebar_articles', ['title' => 'Beliebte ' . $sidebarTitle, 'models' => $popular]) ?>

<?php $this->endBlock() ?>

<style>
    .catalog-all li {
        list-style-type:none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .catalog-all .is-new {
        background:#f04e36;
        color:white;
        font-size: 0.75rem;
        padding: 0 0.25rem;
    }
    .catalog-all a {
        color: #5F5C52
    }
    .catalog-all a:hover {
        color: #f04e36;
    }
</style>
