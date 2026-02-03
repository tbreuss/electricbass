<?php
/**
 * @var yii\web\View $this
 * @var app\models\Lesson $model
 * @var app\models\Lesson[] $similars
 * @var app\models\Lesson[] $latest
 * @var array $breadcrumbs
 * @var string $title
 * @phpstan-var array<int, array{"label": string, "url": string}> $breadcrumbs
 */
?>
<?php $this->params['breadcrumbs'] = $breadcrumbs ?>
<?php $this->title = $title ?>
<?php app\widgets\CanonicalLink::widget(['keepParams' => ['path']]) ?>
<?php $content = app\widgets\Parser::widget(["model" => $model, "attribute" => "text", "tableOfContents" => $model->tableOfContents > 0, "headingPermalink" => $model->headingPermalink > 0]) ?>
<?php $withToc = str_contains($content, '<ul class="table-of-contents">') ?>

<?php if ($withToc): ?>
    <?php /* @phpstan-ignore-line */ $this->context->layout = 'empty' ?>
    <div class="row">
        <div class="col-12 d-block d-md-none">
            <h1><?= $model->title ?></h1>
        </div>
        <div class="col-md-8 content-wrap order-2 order-md-1">
            <h1 class="d-none d-md-block"><?= $model->title ?></h1>
            <?= $this->render('_content', ['model' => $model, 'content' => $content, 'breadcrumbs' => $breadcrumbs]) ?>
        </div>
        <div class="col-md-4 content-wrap order-1 order-md-2 sidebar">
            <div class="sidebar__inner"><!-- filled by javascript --></div>
        </div>
    </div>
<?php else: ?>
    <h1><?= $model->title ?></h1>
    <?= $this->render('_content', ['model' => $model, 'content' => $content, 'breadcrumbs' => $breadcrumbs]) ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <?php if (!empty($similars)): ?>
            <h3 class="sidebarWidget__title">Ähnliche Lektionen</h3>
            <ul class="sidebarWidget__list">
                <?php foreach ($similars as $model): ?>
                    <li class="sidebarWidget__item">
                        <a class="sidebarWidget__link" href="<?= $model->url ?>">
                            <strong><?= $model->title ?></strong><br>
                            <span class="text-muted"><?= join(', ', $model->getTagsAsArray(['Bass-Lektion'])) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h3 class="sidebarWidget__title">Die neuesten Lektionen</h3>
            <ul class="sidebarWidget__list">
                <?php foreach ($latest as $model): ?>
                    <li class="sidebarWidget__item">
                        <a class="sidebarWidget__link" href="<?= $model->url ?>">
                            <strong><?= $model->title ?></strong><br>
                            <span class="text-muted"><?= join(', ', $model->getTagsAsArray(['Bass-Lektion'])) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php $this->endBlock() ?>
<?php endif ?>
