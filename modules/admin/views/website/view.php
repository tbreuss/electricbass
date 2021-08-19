<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Website $model
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="website-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'countryCode',
            'language',
            'title',
            'url',
            'subtitle',
            'abstract:ntext',
            'content:ntext',
            'website',
            'tags',
            'comments',
            'ratings',
            'ratingAvg',
            'hits',
            'status',
            'latitude',
            'longitude',
            'geocodingAddress:ntext',
            'deleted',
            'created',
            'modified',
        ],
    ]) ?>

</div>
