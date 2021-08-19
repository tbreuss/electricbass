<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\LessonSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Lessons';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Lektion hinzufügen', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'path',
            //'autosort',
            //'navtitle',
            'title',
            //'url',
            //'abstract:ntext',
            //'text:ntext',
            //'tags',
            //'renderer',
            //'fotos:ntext',
            //'hideFoto',
            //'comments',
            //'ratings',
            //'ratingAvg',
            //'hits',
            //'featured',
            'deleted',
            'created',
            'modified',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
