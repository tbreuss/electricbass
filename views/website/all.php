<?php

use app\helpers\Html;

$this->params['pageTitle'] = 'Websites zum Thema E-Bass von A-Z';
$this->params['metaDescription'] = 'Umfangreicher Katalog von A-Z mit Marken und Herstellern von E-Bässen und Zubehör, Blogs von Bassist*innen und Websites zum Thema E-Bass.';

?>
<div class="content catalog-all">
    <h1>Websites zum Thema E-Bass von A-Z</h1>

    <div class="row">
        <?php foreach ($entries as $entriesPerInitial): ?>
            <h2><strong><?= $entriesPerInitial['initial'] ?></strong></h2>
            <ul>
                <?php foreach ($entriesPerInitial['entries'] as $entry): ?>
                    <li>
                        <a href="<?= $entry['url'] ?>"><?= $entry['title'] ?></a>
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

<h3 class="sidebarWidget__title"><?= Html::a('Normale Ansicht anzeigen', ['/website/index']) ?></h3>

<?= $this->render('_articles', ['title' => 'Aktuelle Websites', 'models' => $latest]) ?>

<?= $this->render('_articles', ['title' => 'Beliebte Websites', 'models' => $popular]) ?>

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
