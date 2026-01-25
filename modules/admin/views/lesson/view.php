<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Lesson $model
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lesson-view">

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
            'path',
            'autosort',
            'navtitle',
            'title',
            'url',
            'abstract:ntext',
            'text:ntext',
            'tags',
            'renderer',
            'fotos:ntext',
            //'hideFoto',
            'comments',
            'ratings',
            'ratingAvg',
            'hits',
            'featured',
            'deleted',
            'created',
            'modified',
        ],
    ]) ?>

</div>
