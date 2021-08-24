<?php

/**
 * @var app\models\Blog $blog
 * @var app\models\Redirect $redirect
 * @var app\models\Blog[] $similars
 * @var yii\web\View $this
 */

use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Parser;
use app\widgets\Rating;
use app\widgets\SocialBar;
use app\helpers\Html;
use yii\helpers\Url;

$this->title = $blog->title . ' | Blog';
$this->params['pageTitle'] = $blog->pageTitle;
$this->params['metaDescription'] = $blog->metaDescription;
$this->params['breadcrumbs'][] = [
    'label' => 'Blog',
    'url' => Url::previous('blog')
];
$this->params['breadcrumbs'][] = $blog->title;
?>

<div class="content">

    <?php if (!empty($redirect)) : ?>
        <?php
        $this->registerLinkTag([
            'rel' => 'canonical',
            'href' => \app\helpers\Url::to($redirect->to, true)
        ]);
        ?>
    <?php endif; ?>

    <?= $this->render('//_partials/header', [
        'title' => $blog->title,
        'date' => $blog->modified,
        'comments' => $blog->comments
    ]); ?>

    <?php if (!$blog->hideFoto && !empty($blog->fotoUrl)) : ?>
        <p><?= Html::img($blog->fotoUrl, ["width" => 350, "class" => "img-fluid", "alt" => $blog->title]) ?></p>
    <?php endif; ?>

    <?php if (!empty($blog->text)) : ?>
        <?php if (!empty($blog->subtitle)) : ?>
            <?php $blog->text = $blog->subtitle . ' ' . $blog->text; ?>
        <?php endif; ?>
        <div class="widget widget-parser">
            <?= Parser::widget(["model" => $blog, "attribute" => "text"]) ?>
        </div>
    <?php endif; ?>

    <?php if ($blog->hasChanges()) : ?>
        <div class="changes">
            <h3 class="changes__title">Änderungen</h3>
            <ul class="changes__list">
            <?php foreach ($blog->getChanges() as $change) : ?>
                <li class="changes__listItem"><?= $change['date'] ?> <?= $change['text'] ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->render('//_partials/meta', [
        'categories' => [
            ['label' => 'Blog', 'url' => Url::previous("blog")],
        ],
        'tags' => $blog->tags,
    ]); ?>

    <?php if (empty($redirect)) : ?>
        <?= Rating::widget(["tableName" => "blog", "tableId" => $blog->id]) ?>
    <?php endif; ?>

    <?= SocialBar::widget(["id" => $blog->id, "text" => $blog->title]) ?>

    <?php if (!empty($redirect)) : ?>
        <div class="flash flash--info"><?= Html::a('Dieser Artikel wurde in diesen neuen Bereich der Website verschoben', $redirect->to) ?></div>
    <?php endif; ?>

    <?php /*<p><?= Html::a("Alle Blogposts anzeigen", Url::previous("blog")) ?></p>*/ ?>

</div>

<?php if (empty($redirect)) : ?>
    <?= Comments::widget(["tableName" => "blog", "tableId" => $blog->id]) ?>
    <?= Hits::widget(["tableName" => "blog", "tableId" => $blog->id]) ?>
<?php endif; ?>

<?php if (!empty($similars)) : ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title">Ähnliche Blogposts</h3>
        <ul class="sidebarWidget__list">
            <?php foreach ($similars as $model) : ?>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= $model->url ?>">
                    <strong><?= $model->title ?></strong><br>
                    <span class="text-muted"><?= Yii::$app->formatter->asDate($model->modified, 'long') ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->endBlock() ?>
<?php endif; ?>
