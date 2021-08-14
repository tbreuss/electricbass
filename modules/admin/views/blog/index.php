<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Blog hinzufügen', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            //'categoryId',
            //'countryCode',
            //'language',
            'title',
            //'mainTag',
            //'url',
            //'movedTo',
            //'subtitle',
            //'abstract:ntext',
            //'text:ntext',
            //'tags',
            //'renderer',
            //'fotoCopyright',
            //'hideFoto',
            //'comments',
            //'ratings',
            //'ratingAvg',
            //'hits',
            //'featured',
            //'publication',
            'created',
            'modified',
            'deleted',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
