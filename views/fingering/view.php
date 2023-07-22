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
            <?php $roots = Fingerboard::getRoots() ?>
            <?php echo Html::dropDownList('root', $root, array_combine($roots, $roots), ['id' => 'fretboardFormRoot', 'class' => 'fretboardForm__dropdown', 'onchange' => 'this.form.submit();']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormPosition">Position</label>
        <?php $positions = Fingerboard::getPositions() ?>
        <?php echo Html::dropDownList('position', $position, array_combine($positions, $positions), ['id' => 'fretboardFormPosition', 'class' => 'fretboardForm__dropdown', 'onchange' => 'this.form.submit();']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormStrings">Saiten</label>
            <?php echo Html::dropDownList('strings', $strings, ['4' => '4','5' => '5','6' => '6'], ['id' => 'fretboardFormStrings', 'class' => 'fretboardForm__dropdown', 'onchange' => 'this.form.submit();']) ?>
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

    <?php $fb = new Fingerboard($strings, $root, $model->notes, $position); ?>
    <?php #echo $fb->getFingerboard(Yii::getAlias('@web') . '/img/fingerboard/') ?>

    <?php

    $FRETBOARD_STRINGS = array_keys($fb->getOpenStringDefinitions());
    $TUNING = new tebe\tonal\fretboard\Tuning(
        'E-Bass',
        [['C3', 'C'], ['G2', 'G'], ['D2', 'D'], ['A1', 'A'], ['E1', 'E'], ['B0', 'B']]
    );

    if ($strings == 4) {
        $FRETBOARD_STRINGS = array_slice($FRETBOARD_STRINGS, 1, 4);
        $TUNING = new tebe\tonal\fretboard\Tuning(
            'E-Bass',
            [['G2', 'G'], ['D2', 'D'], ['A1', 'A'], ['E1', 'E']]
        );
    }
    if ($strings == 5) {
        $FRETBOARD_STRINGS = array_slice($FRETBOARD_STRINGS, 1, 5);
        $TUNING = new tebe\tonal\fretboard\Tuning(
            'E-Bass',
            [['G2', 'G'], ['D2', 'D'], ['A1', 'A'], ['E1', 'E'], ['B0', 'B']]
        );
    }
    $FRETBOARD_FRETS = range(0, 12);

    $rootFingering = tebe\tonal\fretboard\findLowestNote($TUNING, $root);

    $notesInStandardFormat = $model->getNotesInStandardFormat();
    $notes = array_map(function ($interval) use ($root) {
        $transposed = tebe\tonal\core\distance\transpose($root, $interval);
        if (abs(tebe\tonal\note\get($transposed)->alt) > 1) {
            return [tebe\tonal\note\simplify($transposed), $interval];
        }
        return [$transposed, $interval];
    }, $notesInStandardFormat);

    ?>

    <div style="background-color:#f3f3f3;padding: 0.5rem;margin:1.5rem 0 2rem 0;">
    <table>
        <tr>
            <td style="padding-right: 1rem"><strong>Name</strong>:</td>
            <td><?= $model->title ?></td>
        </tr>        
        <tr>
            <td style="padding-right: 1rem"><strong>Art</strong>:</td>
            <td>
                <?php if ($model->category === 'tonleiter'):
                    ?>Tonleiter<?php
                endif; ?>
                <?php if ($model->category === 'akkord'):
                    ?>Akkord/Arpeggio<?php
                endif; ?>
                <?php if ($model->category === 'intervall'):
                    ?>Intervall<?php
                endif; ?>
            </td>
        </tr>
        <tr>
            <td style="padding-right: 1rem"><strong>Anzahl Töne</strong>:</td>
            <td><?= $model->category === 'tonleiter' ? count(explode('-', $model->notes)) - 1 : count(explode('-', $model->notes)) ?></td>
        </tr>
        <tr>
            <td style="padding-right: 1rem"><strong>Grundton</strong>:</td>
            <td><?= $root ?></td>
        </tr>
        <tr>
            <td style="padding-right: 1rem"><strong>Noten</strong>:</td>
            <td><?= join('&nbsp; ', array_map(fn($note) => $note[0], $notes)) ?></td>
        </tr>
        <tr>
            <td style="padding-right: 1rem"><strong>Intervalle</strong>:</td>
            <td><?= join('&nbsp; ', array_map(fn($note) => $note[1], $notes)) ?></td>
        </tr>
    </table>
    </div>

    <?php

    function replaceStringDef(int $strings, string $note): string
    {
        if ($strings == 6) {
            return str_replace(['B', 'E', 'A', 'D', 'G', 'C'], range(6, 1), $note);
        }
        if ($strings == 5) {
            return str_replace(['B', 'E', 'A', 'D', 'G'], range(5, 1), $note);
        }
        return str_replace(['E', 'A', 'D', 'G'], range(4, 1), $note);
    }

    $fbResults = $fb->getResults();
    ?>

    <?php if (count($fbResults) == 0 || empty($fbResults[0])): ?>        
        <p>Kein Fingersatz in der <?= $position ?>. Lage für einen E-Bass mit <?= $strings ?> Saiten gefunden.</p>
    <?php else: ?>
        <p>Auf einem <?= $strings ?>-saitigen E-Bass ist in der <?= $position ?>. Lage der folgende Fingersatz möglich:</p>
    <?php endif; ?>

    <?php

    foreach ($fbResults as $index => $results) {
        if (empty($results)) {
            continue;
        }
        $fingerings = [];
        foreach ($results as $res) {
            $fingerings[] = $res['fret']
            . '/'
            . replaceStringDef($strings, $res['string'])
            . '-'
            . preg_replace('/[0-9]/', '', $res['absolut']);
        }

        if ($index > 0 && $index < 2) {
            echo "<p>Weitere mögliche Fingersätze oder Griffbilder sind:</p>";
        }

        echo app\widgets\Fretboard::widget([
            'strings' => $FRETBOARD_STRINGS,
            'frets' => $FRETBOARD_FRETS,
            'colors' => 'diatonic',
            'notes' => $fingerings,
            'root' => $rootFingering
        ]);
    }
    ?>

    <?php /** FRETBOARD */ ?>

    <p>Griffbrett mit allen Noten bis zum zwölften Bund:</p>

    <?php

    $fingerings = tebe\tonal\fretboard\findNotes($TUNING, $notes);

    echo app\widgets\Fretboard::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['note'], $fingerings),
        'root' => $rootFingering
    ]);

    ?>

    <p>Griffbrett mit allen Intervallen bis zum zwölften Bund:</p>

    <?php
    echo app\widgets\Fretboard::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['label'], $fingerings),
        'root' => $rootFingering
    ]);

    ?>

    <p>Griffbrett mit allen Intervallen in alternativer Schreibweise bis zum zwölften Bund:</p>

    <?php
    echo app\widgets\Fretboard::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $model::convertNotesToOldFormat($note['label']), $fingerings),
        'root' => $rootFingering
    ]);

    ?>
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

    <?php if (!empty($model->abstract)): ?>
        <div class="markdown"><?= Markdown::process($model->abstract) ?></div>
    <?php endif; ?>

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

<style>
    .fretboard {
        margin-bottom: 2rem;
    }
</style>