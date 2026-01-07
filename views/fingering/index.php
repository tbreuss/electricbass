<?php

/**
 * @var yii\web\View $this
 * @var app\models\Fingering[] $models
 */

use app\helpers\Html;
use app\widgets\CanonicalLink;

$category = '';

$this->title = 'Fingersätze für E-Bass';
$this->params['breadcrumbs'][] = ['label' => 'Tools', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = 'Fingersätze';
CanonicalLink::widget();
?>

<div class="content col-12 col-lg-12 col-xl-11 col-xxl-10">

    <h1>Fingersätze für E-Bass</h1>

    <p>Hier findest du Fingersätze und Griffbilder für die linke Hand für Intervalle, Akkorde (Arpeggios) und Tonleitern für vier-, fünf- und sechssaitige E-Bässe.</p>

    <?php foreach ($models as $mainCategory => $modelsGrouped): ?>
        <h2 id="<?= $mainCategory ?>"><?= Yii::t('app', $mainCategory) ?></h2>
        <?php foreach ($modelsGrouped as $subCategory => $modelGrouped2): ?>
            <?php if ($mainCategory !== 'intervall'): ?>
            <h3 id="<?= $subCategory ?>"><?= Yii::t('app', ucfirst((string)$mainCategory) . ' ' . $subCategory . ' Töne') ?></h3>
            <?php endif; ?>
            <table class="table">
                <colgroup>
                    <col style="width:50%" />
                    <col style="width:50%" />
                </colgroup>
                <!--tr>
                    <td style="width:55%">Name</td>
                    <td style="width:40%">Intervalle</td>
                    <td style="width:5%">Töne</td>
                </tr-->
                <?php foreach ($modelGrouped2 as $model): ?>
                    <tr>
                        <td><?= Html::a($model['title'], $model['url']) ?></td>
                        <td><?= join(' ', $model['notesStandardFormat']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    <?php endforeach; ?>

</div>
<style>
    td:first-child {
        padding-left: 0;
    }
</style>