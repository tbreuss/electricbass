<?php

/**
 * @var app\models\Website[] $models
 */

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
        <h4><?= Html::a($model->title, $model->website, ['target' => '_blank', 'rel' => 'nofollow']) ?></h4>
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
    <h3>Archiv</h3>
    <p>Die folgenden Einträge wurden wegen Geschäftsaufgabe, deaktivierter Website oder anderen Gründen archiviert.</p>
    <table class="table table-bordered table--small">
        <tr>
            <th class="ps-0">Website</th>
            <th>URL</th>
            <th class="pe-0 text-end">Archiviert</th>
        </tr>
    <?php foreach ($models as $model): ?>
        <?php if (!empty($model->archived)): ?>
            <tr>
                <td class="ps-0"><?= $model->title ?></td>
                <td><?= $model->website ?></td>
                <td class="pe-0 text-end"><?= Yii::$app->formatter->asDate($model->archived) ?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </table>
<?php endif; ?>

</div>
<?php Spaceless::end() ?>
