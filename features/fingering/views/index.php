<?php

/**
 * @var yii\web\View $this
 * @var string $category
 * @var string $categoryPlural
 * @var app\features\fingering\models\Fingering[] $models
 */

$this->title = 'Fingersätze für E-Bass';
$this->params['breadcrumbs'][] = ['label' => 'Werkzeuge', 'url' => '/tools'];
if (count($models) > 0) {
    $this->params['breadcrumbs'][] = ['label' => 'Fingersätze', 'url' => ['fingering/index']];
    $this->params['breadcrumbs'][] = $categoryPlural;
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
    <h1>Fingersätze <?= $categoryPlural ?></h1>
    <select class="form-select mb-3" onchange="window.location.href = this.value">
        <option selected disabled><?= $category ?> auswählen</option>
        <?php foreach ($models as $model): ?>
            <option value="<?= $model->url ?>"><?= $model->title ?></option>
        <?php endforeach ?>
    </select>
    <div class="row">
    <?php foreach ($models as $model): ?>
        <div class="col-12 col-md-6">
        <?= app\helpers\Html::a($model->title, $model->url, ['style' => 'color:#222']) ?>
        <a href="<?= $model->url ?>"><?= app\features\fingering\Fretboard::widget([
                'showDots' => false,
                'showFretNumbers' => false,
                'showStringNames' => false,
                'colors' => 'default',
                'strings' => ['G', 'D', 'A', 'E'],
                'frets' => range(0, 12),
                'notes' => preg_split('/\s+/', $model->fingering),
        ]); ?></a>
        </div>
    <?php endforeach ?>
    </div>
<?php endif ?>
