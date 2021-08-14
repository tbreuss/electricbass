<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\WebsiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Websites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Website', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            //'countryCode',
            //'language',
            'title',
            //'url',
            //'subtitle',
            //'abstract:ntext',
            //'content:ntext',
            'website',
            //'tags',
            //'comments',
            //'ratings',
            //'ratingAvg',
            //'hits',
            //'status',
            //'latitude',
            //'longitude',
            //'geocodingAddress:ntext',
            'created',
            'modified',
            'deleted',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
