<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\VideoSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            //'eid',
            //'oid',
            //'countryCode',
            //'language',
            'platform',
            'key',
            //'width',
            //'height',
            'title',
            //'url',
            //'abstract',
            //'text:ntext',
            //'tags',
            //'comments',
            //'ratings',
            //'ratingAvg',
            //'hits',
            'deleted',
            'created',
            'modified',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
