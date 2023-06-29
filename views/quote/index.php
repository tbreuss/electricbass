<?php

/**
 * @var yii\web\View $this
 * @var app\models\Quote[] $models
 */

use app\widgets\Comments;
use app\widgets\Rating;
use app\widgets\SocialBar;

$this->title = 'Zitate von Bassisten';
$this->params['breadcrumbs'][] = 'Zitate von Bassisten';
$this->params['pageTitle'] = 'Die besten Zitate von berühmten Bassisten';
$this->params['metaDescription'] = 'Die Sammlung mit den besten Zitaten berühmter Bassisten und Musiker zum Thema Bass. Welcher Bassist hat was wann gesagt? Hier wirst du garantiert fündig!';

?>
<div class="content">

    <h1>Zitate von Bassisten</h1>

    <?php // ListSummary::widget(['pagination' => $pagination, 'sort' => $sort]) ?>

    <div class="widget widget-listview">
        <?php foreach ($models as $i => $model): ?>
            <div class="row">
                <div class="col-sm-12">
                    <figure>
                        <blockquote class="blockquote">
                            <p><?= nl2br(strip_tags($model->text)) ?></p>
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            <?= $model->author ?>
                        </figcaption>
                    </figure>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php // LinkPager::widget(['pagination' => $pagination]) ?>

<?= $this->render('//_partials/meta', [
    'categories' => [
        ['label' => 'Zitate von Bassisten', 'url' => ['quote/index']]
    ]
]); ?>

<?= Rating::widget(["tableName" => "quote", "tableId" => 0]) ?>

<?= SocialBar::widget(["id" => null, "text" => "Zitate von Bassisten"]) ?>

<?= Comments::widget(["tableName" => "quote", "tableId" => 0]) ?>
