<?php

/**
 * @var yii\web\View $this
 * @var string $category
 * @var app\models\Fingering[] $models
 */

use app\widgets\CanonicalLink;

$this->blocks['title'] = $this->title = 'Fingersätze für E-Bass';
$this->params['breadcrumbs'][] = ['label' => 'Werkzeuge', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = 'Fingersätze';
CanonicalLink::widget();
?>

<?php $this->beginBlock('tableOfContents') ?>
<ul class="table-of-contents">
    <li><a href="<?= app\helpers\Url::to(['/fingering/index', 'category' => 'arpeggio']) ?>">Arpeggios</a></li>
    <li><a href="<?= app\helpers\Url::to(['/fingering/index', 'category' => 'intervall']) ?>">Intervalle</a></li>
    <li><a href="<?= app\helpers\Url::to(['/fingering/index', 'category' => 'tonleiter']) ?>">Tonleitern</a></li>
</ul>
<?php $this->endBlock() ?>

<?php if (count($models) === 0): ?>
    <p>Hier findest du Fingersätze und Griffbilder für die linke Hand für Intervalle, Arpeggios und Tonleitern für vier-, fünf- und sechssaitige E-Bässe.</p>
    <?php else: ?>
    <table class="table">
        <colgroup>
            <col style="width:50%" />
            <col style="width:50%" />
        </colgroup>
        <?php foreach ($models as $model): ?>
            <tr>
                <td><?= app\helpers\Html::a($model->title, $model->url) ?></td>
                <td><?= join(' ', $model->getNotesInStandardFormat()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif ?>
