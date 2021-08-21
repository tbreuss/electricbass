<?php
/**
 * @var yii\web\View $this
 * @var app\models\Album[] $latest
 * @var app\models\Album[] $popular
 * @var array $entries
 * @var string $title
 */

use app\helpers\Html;

$this->params['pageTitle'] = 'Inspirierende Musikalben von E-Bassist*innen von A-Z';
$this->params['metaDescription'] = 'Inspirierende und hörenswerte Musikalben berühmter E-Bassisten von A-Z. Unentdeckte Alben und Songs von Bassisten wie du und ich.';

?>
<div class="content catalog-all">
    <h1>Musikalben von E-Bassisten von A-Z</h1>

    <div class="row">
        <?php foreach ($entries as $entriesPerInitial): ?>
            <h2><strong><?= $entriesPerInitial['initial'] ?></strong></h2>
            <ul>
            <?php foreach ($entriesPerInitial['entries'] as $entry): ?>
                <li>
                    <a href="<?= $entry['url'] ?>"><?= $entry['artist'] ?> - <?= $entry['title'] ?></a>
                    <?php if ($entry['isNew']): ?>
                        <span class="is-new">NEU</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    </div>
</div>

<?php $this->beginBlock('sidebar') ?>

<h3 class="sidebarWidget__title"><?= Html::a('Normale Ansicht anzeigen', ['/album/index']) ?></h3>

<?= $this->render('_articles', ['title' => 'Aktuelle Musikalben', 'models' => $latest]) ?>

<?= $this->render('_articles', ['title' => 'Beliebte Musikalben', 'models' => $popular]) ?>

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
