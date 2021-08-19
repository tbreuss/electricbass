<?php

/**
 * @var  yii\web\View $this
 * @var  app\modules\admin\models\Website $model
 */

use yii\helpers\Html;

$this->title = 'Update Website: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="website-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
