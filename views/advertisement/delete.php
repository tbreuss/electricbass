<?php

/**
 * @var app\models\Advertisement $model
 * @var yii\web\View $this
 */

use app\helpers\Html;

$this->title = 'Inserat löschen';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = 'Inserat löschen';
$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex']);
?>

<div class="content">
    <h1><?php echo $this->title ?></h1>

    <p>Soll dein Inserat wirklich gelöscht werden?</p>
    <b><?php echo $model->title ?></b><br>
    <?php echo ($model->date) ?><br />
    <?php echo $model->getShortenedText(300) ?></p>

    <p>
        <a class="button button--primary" href="<?php echo $model->createDeleteUrl(1) ?>">Inserat löschen</a>
        <?= Html::a('Abbrechen', ['/advertisement/index'], ['class' => 'button button--secondary']) ?>
    </p>
</div>
