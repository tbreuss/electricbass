<?php

/**
 * @var yii\web\View $this
 * @var string $category
 * @var \app\feature\fingering\models\Fingering[] $models
 */

$this->title = 'Fingersätze für E-Bass';
$this->params['breadcrumbs'][] = ['label' => 'Werkzeuge', 'url' => ['tool/index']];
if (count($models) > 0) {
    $this->params['breadcrumbs'][] = ['label' => 'Fingersätze', 'url' => ['fingering/index']];
    $this->params['breadcrumbs'][] = Yii::t('app', $category);
} else {
    $this->params['breadcrumbs'][] = 'Fingersätze';
}
?>

<?php $this->beginBlock('sidebar') ?>
<ul class="table-of-contents d-none d-md-block">
    <li><a href="<?= app\helpers\Url::to(['/fingering/index', 'category' => 'arpeggio']) ?>">Arpeggios</a></li>
    <li><a href="<?= app\helpers\Url::to(['/fingering/index', 'category' => 'intervall']) ?>">Intervalle</a></li>
    <li><a href="<?= app\helpers\Url::to(['/fingering/index', 'category' => 'tonleiter']) ?>">Tonleitern</a></li>
</ul>
<?php $this->endBlock() ?>

<?php if (count($models) === 0): ?>
    <h1>Fingersätze</h1>
    <p>Hier findest du Fingersätze und Griffbilder für die linke Hand für Intervalle, Arpeggios und Tonleitern für vier-, fünf- und sechssaitige E-Bässe.</p>
    <h3 class="title"><?= app\helpers\Html::a('Arpeggios', ['/fingering/index', 'category' => 'arpeggio']) ?></h3>
    <p>Fingersätze für Arpeggios für vier-, fünf- und sechssaitige E-Bässe.</p>
    <hr>
    <h3 class="title"><?= app\helpers\Html::a('Intervalle', ['/fingering/index', 'category' => 'intervall']) ?></h3>
    <p>Fingersätze für Intervalle für vier-, fünf- und sechssaitige E-Bässe.</p>
    <hr>
    <h3 class="title"><?= app\helpers\Html::a('Tonleitern', ['/fingering/index', 'category' => 'tonleiter']) ?></h3>
    <p>Fingersätze für Tonleitern für vier-, fünf- und sechssaitige E-Bässe.</p>
<?php else: ?>
    <h1>Fingersätze <?= Yii::t('app', $category) ?></h1>
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
