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

    <?php if (empty($selectedCategory)) : ?>
        <p>In diesem E-Bass Lexikon findest Du Fachbegriffe, Abkürzungen und Erklärungen zum Instrument E-Bass, dem Equipment, der Hardware und weitere Begriffe der Musik. Das Lexikon ist in Kategorien eingeteilt und alphabetisch sortiert. Fehlt ein Begriff oder eine Bezeichnung? <?php echo Html::a('Sende mir eine kurze E-Mail', array('/site/contact')) ?></p>
    <?php endif; ?>

    <?php
    $category = '';
    $delim = '';
    foreach ($glossars as $i => $glossar) {
        if ($category != $glossar->category) {
            $category = $glossar->category;
            $delim = '';
            if ($i > 0) {
                echo Html::endTag('p');
            }
            if (!empty($selectedCategory)) {
                $this->title = $category . ' | ' . $this->title;
                $this->params['breadcrumbs'][count($this->params['breadcrumbs']) - 1] = ['label' => 'Glossar', 'url' => ['glossar/index']];
                $this->params['breadcrumbs'][] = $category;
            }
            echo Html::tag('h3', $category);
            echo Html::beginTag('p');
        }
        echo $delim . Html::a($glossar->title, $glossar->url);
        $delim = ', ';
    }
    echo Html::beginTag('p');

    echo $this->render('_sidebar', array('selectedCategory' => $selectedCategory));

    ?>
</div>
