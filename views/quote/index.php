<?php

/**
 * @var View $this
 * @var Quote[] $models
 */

use app\models\Quote;
use app\widgets\Comments;
use app\widgets\Rating;
use app\widgets\SocialBar;
use yii\web\View;

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

<style>
    .blockquote {
        margin-bottom: 1rem;
        font-size: 1.25rem;
        line-height: 1.3;
        color: #222222;
    }
    .blockquote-footer {
        margin-top: -1rem;
        margin-bottom: 1rem;
        font-size: .875em;
        color: #6c757d;
    }
    .blockquote-footer::before {
        content: "— ";
    }
</style>

<?php // LinkPager::widget(['pagination' => $pagination]) ?>

<?= $this->render('//_partials/meta', [
    'categories' => [
        ['label' => 'Zitate von Bassisten', 'url' => ['quote/index']]
    ]
]); ?>

<?= Rating::widget(["tableName" => "quote", "tableId" => 0]) ?>

<?= SocialBar::widget(["id" => null, "text" => "Zitate von Bassisten"]) ?>

<?= Comments::widget(["tableName" => "quote", "tableId" => 0]) ?>
