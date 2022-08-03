<?php

/**
 * @var app\models\Advertisement $model
 * @var yii\web\View $this
 */

use app\helpers\Html;
use app\widgets\Comments;
use app\widgets\GoogleMaps;
use app\widgets\Hits;
use app\widgets\Rating;
use app\widgets\SocialBar;
use yii\helpers\Markdown;

#$this->metaDescription = mb_strimwidth(str_replace("\n", ' ' , strip_tags(Markdown::process($model->longtext))), 0, 240, '...', 'UTF-8');

$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = $model->title;
$this->title = $model->getPageTitle();
?>

<?php if ($model->expired || $model->deleted || $model->hidden): ?>
    <div class="content">

        <h1><?= stripslashes($model->title) ?></h1>

        <div class="markdown"><?= strip_tags(Markdown::process($model->longtext), '<p><strong><b><ul><li><br>') ?></div>

        <div class="flash flash--danger">Dieses Inserat ist abgelaufen oder wurde gelöscht.</div>

        <p><?= Html::a('Aktuelle Inserate anzeigen', ['advertisement/index']) ?></p>
    </div>

<?php else: ?>
    <div class="content">

        <h1><?= stripslashes($model->title) ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <div class="flash flash--success">Vielen Dank! Wir haben deine Nachricht an <?= $model->name ?> weitergeleitet.</div>
        <?php endif; ?>

        <?php if (($photo = $model->getPhoto()) != ''): ?>
            <div class="image">
                <?= Html::img('@web/' . $photo, ["width" => 450, "alt" => $model->title, "class" => "img-fluid"]) ?>
            </div>
        <?php endif; ?>

        <div class="markdown"><?= strip_tags(Markdown::process($model->longtext), '<p><strong><b><ul><li><br>') ?></div>

        <?php if (!empty($model->email)): ?>
            <p class="text-center"><?= Html::a('Anbieter kontaktieren', ['advertisement/contact', 'id' => $model->id], ['class' => 'button button--danger button--big']); ?></p>
        <?php endif; ?>

        <h2>Infos</h2>
        <?php $infos = $model->getInfos() ?>
        <table class="table table--small">
            <tr>
                <td>Kategorie</td>
                <td><?= $model->getCategory() ?></td>
            </tr>
            <tr>
                <td>Erstellt</td>
                <td><?= Yii::$app->formatter->asDate($model->date, 'long') ?></td>
            </tr>
        <?php foreach ($infos as $label => $value): ?>
            <tr>
                <td><?= $label ?></td>
                <td><?= $value ?></td>
            </tr>
        <?php endforeach; ?>
        </table>

        <?= GoogleMaps::widget([
            'latitude' => $model->latitude,
            'longitude' => $model->longitude,
            'title' => 'Standort',
        ]) ?>

        <?= $this->render('//_partials/meta', [
            'categories' => [['label' => 'Kleinanzeigen', 'url' => ['/advertisement/index']]],
            'tags' => [],
        ]); ?>

        <?= Rating::widget(["tableName" => "advertisement", "tableId" => $model->id]) ?>

        <?= SocialBar::widget(["text" => $model->title]) ?>

    </div>

    <?= Comments::widget(["tableName" => "advertisement", "tableId" => $model->id]) ?>

    <?= Hits::widget(["tableName" => "advertisement", "tableId" => $model->id]) ?>

<?php endif; ?>
