<?php

/**
 * @var yii\web\View $this
 * @var \models\Quote[] $models
 */

$this->title = 'Zitate von Bassisten';
$this->params['breadcrumbs'][] = 'Zitate von Bassisten';
$this->params['pageTitle'] = 'Die besten Zitate von berühmten Bassisten';
$this->params['metaDescription'] = 'Die Sammlung mit den besten Zitaten berühmter Bassisten und Musiker zum Thema Bass. Welcher Bassist hat was wann gesagt? Hier wirst du garantiert fündig!';

?>
<div class="content">
    <h1>Zitate berühmter Bassisten</h1>
    <div class="widget widget-listview">
        <?php foreach ($models as $i => $model): ?>
            <figure>
                <blockquote class="blockquote">
                    <p><?= nl2br(strip_tags($model->text)) ?></p>
                </blockquote>
                <figcaption class="blockquote-footer">
                    <?= $model->author ?>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</div>

<?= app\feature\rating\RatingShare::widget(["tableName" => "quote", "tableId" => 0]) ?>

<?= app\feature\comment\Widget::widget(["tableName" => "quote", "tableId" => 0]) ?>
