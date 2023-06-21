<?php

/**
 * @var yii\web\View $this
 * @var app\models\Page $page
 */

use yii\helpers\Markdown;
use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Rating;
use app\widgets\SocialBar;

$this->title = $page->title;

// TODO breadcrumbs

?>

<h1><?= $page->title ?></h1>
<div class="markdown"><?= Markdown::process($page->content) ?></div>

<?= Rating::widget(["tableName" => "page", "tableId" => $page->id]) ?>

<?= SocialBar::widget(["id" => $page->id, "text" => $page->title]) ?>

<?= Comments::widget(["tableName" => "page", "tableId" => $page->id]) ?>

<?= Hits::widget(["tableName" => "page", "tableId" => $page->id]) ?>
