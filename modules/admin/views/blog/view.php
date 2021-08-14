<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Blog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Aktualisieren', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Löschen', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Soll dieser Eintrag gelöscht werden?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'categoryId',
            'countryCode',
            'language',
            'title',
            'mainTag',
            'url',
            'movedTo',
            'subtitle',
            'abstract:ntext',
            'text:ntext',
            'pageTitle',
            'metaDescription:ntext',
            'tags',
            'renderer',
            'fotoCopyright',
            'hideFoto',
            'comments',
            'ratings',
            'ratingAvg',
            'hits',
            'featured',
            'publication',
            'deleted',
            'created',
            'modified',
        ],
    ]) ?>

</div>
