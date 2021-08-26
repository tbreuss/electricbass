<?php

/**
 * @var yii\web\View $this
 * @var app\models\Fingering $model
 */

use app\components\Fingerboard;
use app\helpers\Html;
use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Rating;
use app\widgets\SocialBar;
use yii\helpers\Markdown;

$this->title = $model->title . ' | Fingersätze';
$this->params['breadcrumbs'][] = ['label' => 'Tools', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = ['label' => 'Fingersätze', 'url' => ['fingering/index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="content">

    <h1><?= $model->title ?></h1>

    <?php

    $root = isset($_GET['root']) ? $_GET['root'] : $model->root;
    $position = isset($_GET['position']) ? $_GET['position'] : $model->position;
    $strings = isset($_GET['strings']) ? $_GET['strings'] : $model->strings;

    ?>

    <?php if (isset($_GET['root'], $_GET['position'], $_GET['strings'])): ?>
        <?php #$this->metaNoIndex = true ?>
    <?php endif; ?>

    <form class="fretboardForm" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="get">
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormRoot">Grundton</label>
            <?php $roots = array_reverse(Fingerboard::getRoots()) ?>
            <?php echo Html::dropDownList('root', $root, array_combine($roots, $roots), ['id' => 'fretboardFormRoot', 'class' => 'fretboardForm__dropdown']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormPosition">Position</label>
        <?php $positions = Fingerboard::getPositions() ?>
        <?php echo Html::dropDownList('position', $position, array_combine($positions, $positions), ['id' => 'fretboardFormPosition', 'class' => 'fretboardForm__dropdown']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormStrings">Saiten</label>
            <?php echo Html::dropDownList('strings', $strings, ['4' => '4','5' => '5','6' => '6'], ['id' => 'fretboardFormStrings', 'class' => 'fretboardForm__dropdown']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label">&nbsp;</label>
            <?php echo Html::submitButton('Anzeigen', ['name' => null,'class' => 'fretboardForm__submit']) ?>
        </div>
    </form>
    <style>
        .fretboardForm {
            display: flex;
            /*flex-direction: row;*/
            justify-content: normal;
        }
        .fretboardForm__column {
            margin-right: 1rem;
        }
        .fretboardForm__label {
            display: block;
        }
        .fretboardForm__dropdown {
            width: 100%;
        }
        .fretboardForm__submit {
            background-color: #ffffff;
            border: 1px solid rgb(118, 118, 118);
            font-size: 0.8rem;
        }
    </style>

    <div style="margin-top: 2rem; margin-bottom:2rem;overflow:auto">
        <?php $fb = new Fingerboard($strings, $root, $model->notes, $position); ?>
        <?php echo $fb->getFingerboard(Yii::getAlias('@web') . '/img/fingerboard/') ?>
    </div>

    <div class="markdown"><?= Markdown::process($model->abstract) ?></div>

    <?php /*$rtttl = $fb->toRTTTL() ?>
    <?php if($rtttl): ?>
        <div style="margin-bottom:1.4em">

            <?php Yii::import('application.vendors.midi_class.*'); ?>
            <?php require_once('classes/midi_rttl.class.php') ?>

            <?php
            $midi = new MidiRttl();
            $midi->importRttl($fb->toRTTTL(), 33);

            $basename = empty($model->url) ? $model->id : $model->url;
            $root = $fb->getAbsoluteRoot(true);
            $file = "media/midi/fretboard/{$basename}-{$root}.mid";
            if(!is_file($file)) $midi->saveMidFile($file, 0666);

            $midi->playMidFile(baseUrl().'/'.$file,1,0,0);
            ?>
        </div>
    <?php endif;*/ ?>

    <?= $this->render('//_partials/meta', [
        'categories' => [
            ['label' => 'Tools', 'url' => ['/tools']],
            ['label' => 'Fingersätze', 'url' => ['/fingering/index']]
        ],
        'tags' => $model->tags,
    ]); ?>

    <?= Rating::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

    <?= SocialBar::widget(["id" => $model->id, "text" => $model->title]) ?>

</div>

<?= Comments::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

<?= Hits::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

<?php if (!empty($similars)): ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title">Ähnliche Fingersätze</h3>
        <ul class="sidebarWidget__list">
            <?php foreach ($similars as $model): ?>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= $model->url ?>">
                    <strong><?= $model->title ?></strong><br>
                    <span class="text-muted"><?= join(', ', $model->getTagsAsArray(['Bass-Griff'])) ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php $this->endBlock() ?>
<?php endif; ?>
