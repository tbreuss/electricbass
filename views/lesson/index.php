<?php

/**
 * @var View $this
 * @var Lesson $model
 * @var Lesson[] $similars
 * @var Lesson[] $latest
 * @var array $breadcrumbs
 */

use app\models\Lesson;
use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Parser;
use app\widgets\Rating;
use app\widgets\RatingReadOnly;
use app\widgets\SocialBar;
use yii\web\View;

$this->params['breadcrumbs'] = $breadcrumbs;
$this->title = $model->title . ' | Lektionen'

?>

<div class="content">

    <h1><?= $model->title ?></h1>

    <?php if(!empty($model->text)): ?>
        <div class="widget widget-parser">
            <?= Parser::widget(["model" => $model, "attribute" => "text"]) ?>
        </div>

        <?= $this->render('//_partials/meta', [
            'categories' => $breadcrumbs,
            'tags' => $model->tags,
        ]); ?>

        <?= Rating::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>
    <?php else: ?>
        <?= RatingReadOnly::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>
    <?php endif; ?>

    <?= SocialBar::widget(["id" => $model->id, "text" => $model->title]) ?>

</div>

<?= Comments::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>

<?= Hits::widget(["tableName" => "lesson", "tableId" => $model->id]) ?>

<?php $this->beginBlock('sidebar') ?>
<div class="sidebarWidget">
    <?php if (!empty($similars)): ?>
        <h3 class="sidebarWidget__title">Ähnliche Lektionen</h3>
        <ul class="sidebarWidget__list">
            <?php foreach($similars AS $model): ?>
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
            <?php foreach($latest AS $model): ?>
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
