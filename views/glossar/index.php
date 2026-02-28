<?php

/**
 * @var yii\web\View $this
 * @var app\models\Glossar[] $glossars
 * @var string $selectedCategory
 */

use app\helpers\Html;

$this->title = 'Glossar';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content">

    <h1>E-Bass-Glossar</h1>

    <p>In diesem E-Bass Lexikon findest Du Fachbegriffe, Abkürzungen und Erklärungen zum Instrument E-Bass, dem Equipment, der Hardware und weitere Begriffe der Musik. Das Lexikon ist in Kategorien eingeteilt und alphabetisch sortiert. Fehlt ein Begriff oder eine Bezeichnung? <?php echo Html::a('Sende mir eine kurze E-Mail', ['/site/contact']) ?></p>

    <?php foreach ($glossarsByCategory as $category => $glossars): ?>
        <h2><?= $category ?></h2>
        <?php foreach ($glossars as $glossar): ?>
            <h3><?= $glossar->title ?></h3>
            <?= app\widgets\Parser::widget(["model" => $glossar, "attribute" => "content"]) ?>
        <?php endforeach ?>
    <?php endforeach ?>
</div>
