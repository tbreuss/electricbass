<?php

use app\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\Spaceless;

?>

<?php if (empty($models)) {
    return;
} ?>

<?php Spaceless::begin() ?>
<div class="shortcode shortcode--websites">

<?php $hasArchived = false; ?>    
<?php foreach ($models as $model): ?>
    <?php if (!empty($model->archived)): ?>
        <?php $hasArchived = true; ?>
    <?php else: ?>
        <h4><?= Html::a($model->title, $model->url) ?></h4>
        <?php if (!empty($model->subtitle)): ?>
            <p class="mb-0"><i><?= $model->subtitle ?></i></p>
        <?php endif; ?>
        <?php if (!empty($model->abstract)): ?>
            <p><?= strip_tags(Markdown::process($model->abstract)) ?></p>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>

<?php if ($hasArchived): ?>
    <hr>
    <h4>Archiv</h4>
    <p>Die folgenden Einträge wurden wegen Geschäftsaufgabe, deaktivierter Website oder anderen Gründen archiviert.</p>
    <table class="table table-bordered">
        <tr>
            <th>Website</th>
            <th>Archivdatum</th>
        </tr>        
    <?php foreach ($models as $model): ?>
        <?php if (!empty($model->archived)): ?>
            <tr>
                <td><?= Html::a($model->title, $model->url) ?></td>
                <td><?= Yii::$app->formatter->asDate($model->archived, 'long') ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </table> 
<?php endif; ?>

</div>
<?php Spaceless::end() ?>
