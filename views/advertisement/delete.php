<?php
use app\helpers\Html;
use app\models\Advertisement;
use yii\web\View;

/**
 * @var Advertisement $model
 * @var View $this
 */
?>
<?php
$this->title = 'Inserat löschen';
$this->params['breadcrumbs'][] = ['label' => 'Bassmarkt', 'url' => ['advertisement/index']];
$this->params['breadcrumbs'][] = 'Inserat löschen';
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
