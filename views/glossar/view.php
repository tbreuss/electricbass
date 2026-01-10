<?php

/**
 * @var yii\web\View $this
 * @var app\models\Glossar $glossar
 * @var app\models\Glossar $next
 * @var app\models\Glossar $previous
 * @var string $selectedCategory
 */

use app\helpers\Html;
use app\widgets\Comments;
use app\widgets\Parser;
use app\widgets\Rating;
use app\widgets\SocialBar;
use app\widgets\Hits;

$this->title = $glossar->title . ' | ' . $glossar->category . ' | Glossar';
$this->params['breadcrumbs'][] = ['label' => 'Glossar', 'url' => ['glossar/index']];
$this->params['breadcrumbs'][] = ['label' => $glossar->category, 'url' => ['glossar/index', 'category' => strtolower($glossar->category)]];
;
$this->params['breadcrumbs'][] = $glossar->title;
?>

<div class="content">

    <h1><?php echo $glossar->title ?></h1>
    <?php #$this->metaDescription = strip_tags(Markdown::process($glossar->abstract)) ?>

    <p>In <?php echo Html::a('Glossar', ['/glossar']) ?> / <?php echo Html::a($glossar->category, ['/glossar/index', 'category' => $selectedCategory]) ?></p>

    <?php if ($glossar->hasDefaultImage()): ?>
        <p><?= Html::img($glossar->getDefaultImage('@web'), ["width" => 350, "class" => "img-fluid", "alt" => $glossar->title]) ?></p>
    <?php endif; ?>

    <?= Parser::widget(["model" => $glossar, "attribute" => "content"]) ?>

    <ul class="pager">
        <?php if ($next != null): ?>
            <li class="next">
                <?php echo Html::a($next->title, $next->url, ['class' => 'button button--small']) ?>
            </li>
        <?php endif; ?>
        <?php if ($previous !== null): ?>
            <li class="previous">
                <?php echo Html::a($previous->title, $previous->url, ['class' => 'button button--small']) ?>
            </li>
        <?php endif; ?>
    </ul>

    <?php echo $this->render('_sidebar', ['glossar' => $glossar, 'selectedCategory' => $selectedCategory]) ?>
</div>

<?= Rating::widget(["tableName" => "glossar", "tableId" => $glossar->id]) ?>

<?= SocialBar::widget(["id" => $glossar->id, "text" => $glossar->title]) ?>

<?= Hits::widget(["tableName" => "glossar", "tableId" => $glossar->id]) ?>

<?= Comments::widget(["tableName" => "glossar", "tableId" => $glossar->id]) ?>

<style>
    .pager .next {
        float:right;
    }
    .pager {
        list-style-type:none;
        margin:0;
        padding:0;
    }
</style>
