<?php

/**
 * @var yii\web\View $this
 * @var app\modules\admin\models\Website $model
 */

use yii\helpers\Html;

$this->title = 'Create Website';
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="website-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
