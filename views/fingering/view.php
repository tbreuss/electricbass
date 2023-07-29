<?php

/**
 * @var yii\web\View $this
 * @var app\models\Fingering $model
 */

use app\helpers\Html;
use app\helpers\Url;
use app\widgets\Comments;
use app\widgets\Hits;
use app\widgets\Rating;
use app\widgets\SocialBar;
use yii\helpers\Markdown;

use const tebe\tonal\fretboard\EXPAND_NO;
use const tebe\tonal\fretboard\EXPAND_HIGHER;

$this->title = $model->title . ' | Fingersätze';
$this->params['breadcrumbs'][] = ['label' => 'Tools', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = ['label' => 'Fingersätze', 'url' => ['fingering/index']];
$this->params['breadcrumbs'][] = $model->title;

/**
 * @return string[][]
 */
function getOpenStringDefinitions(): array
{
    $openStrings = [];
    $openStrings['C'] = ['F' => '', 'N' => 'C3', 'S' => ''];
    $openStrings['G'] = ['F' => '', 'N' => 'G2', 'S' => ''];
    $openStrings['D'] = ['F' => '', 'N' => 'D2', 'S' => ''];
    $openStrings['A'] = ['F' => '', 'N' => 'A1', 'S' => ''];
    $openStrings['E'] = ['F' => '', 'N' => 'E1', 'S' => ''];
    $openStrings['B'] = ['F' => '', 'N' => 'B0', 'S' => ''];
    return $openStrings;
}

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

?>

<div class="content col-12 col-lg-12 col-xl-11 col-xxl-10">

    <h1><?= $model->title ?></h1>

    <?php

    $root = isset($_GET['root']) ? $_GET['root'] : $model->root;
    $strings = isset($_GET['strings']) ? $_GET['strings'] : $model->strings;
    $expand = isset($_GET['expand']) ? $_GET['expand'] : 0;

    ?>

    <?php if (isset($_GET['root'], $_GET['position'], $_GET['strings'])): ?>
        <?php #$this->metaNoIndex = true ?>
    <?php endif; ?>

    <form class="fretboardForm" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="get">
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormRoot">Grundton</label>
            <?php $roots = ['C', 'G', 'D', 'A', 'E', 'B', 'F#', 'Gb', 'Db', 'Ab', 'Eb', 'Bb', 'F'] ?>
            <?php echo Html::dropDownList('root', $root, array_combine($roots, $roots), ['id' => 'fretboardFormRoot', 'class' => 'fretboardForm__dropdown', 'onchange' => 'this.form.submit();']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormStrings">Saiten</label>
            <?php echo Html::dropDownList('strings', $strings, ['4' => '4','5' => '5','6' => '6'], ['id' => 'fretboardFormStrings', 'class' => 'fretboardForm__dropdown', 'onchange' => 'this.form.submit();']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormExpand">Gestreckte Lage</label>
            <?php echo Html::dropDownList('expand', $expand, ['0' => 'Nein', '1' => 'Ja'], ['id' => 'fretboardFormExpand', 'class' => 'fretboardForm__dropdown', 'onchange' => 'this.form.submit();']) ?>
        </div>
    </form>

    <?php

    $FRETBOARD_STRINGS = array_keys(getOpenStringDefinitions());
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
        if (abs(tebe\tonal\note\get($transposed)->alt) > 1) { // @phpstan-ignore-line
            return [tebe\tonal\note\simplify($transposed), $interval];
        }
        return [$transposed, $interval];
    }, $notesInStandardFormat);

    $fingerings = tebe\tonal\fretboard\findNotes($TUNING, $notes);
    $lowest = tebe\tonal\fretboard\findLowestNote($TUNING, $root);

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

    <?php if (!empty($model->fingering)): ?>
        <h2>Fingersatz</h3>
        <p>Der meist verwendete Fingersatz <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> sieht wie folgt aus.</p>
        <?= app\widgets\Fretboard::widget([
            'showDots' => false,
            'showFretNumbers' => false,
            'showStringNames' => false,
            'colors' => 'default',
            'strings' => $FRETBOARD_STRINGS,
            'frets' => $FRETBOARD_FRETS,
            'notes' => preg_split('/\s+/', $model->fingering),
        ]); ?>
    <?php endif; ?>
    
    <?php

    $allPossibilitites = [];
    $expandPosition = $expand;
    foreach (range(1, 8) as $pos) {
        $possibilities = \tebe\tonal\fretboard\get_all_possibilities($notes, $fingerings, $pos, $expandPosition);
        if (!empty($possibilities)) {
            $allPossibilitites[$pos] = $possibilities;
        }
    }
    ?>

    <?php if (count($allPossibilitites) == 0): ?>
        <h2>Lagen</h3>
        <p>
            Für <?= $model->title_accusative ? $model->title_accusative : $model->categoryAsAccusative() ?> mit Grundton <?= $root ?> auf dem <?= $strings ?>-saitigen E-Bass wurde kein Fingersatz gefunden.
            Probiere es mit der <a href="?root=<?= $root ?>&strings=<?= $strings ?>&expand=1">gestreckten Lage</a>.
        </p>
    <?php else: ?>
        <h2>Lagen</h3>
        <p>Für <?= $model->title_accusative ? $model->title_accusative : $model->categoryAsAccusative() ?> mit Grundton <?= $root ?> gibt es auf dem <?= $strings ?>-saitigen E-Bass Griffbilder in den folgenden Lagen.</p>
        <?php foreach ($allPossibilitites as $pos => $possibilitiesPerPosition): ?>
            <?php foreach ($possibilitiesPerPosition as $result): ?>
                <?= app\widgets\Fretboard::widget([
                    'position' => $pos,
                    'expandPosition' => $expandPosition,
                    'strings' => $FRETBOARD_STRINGS,
                    'frets' => $FRETBOARD_FRETS,
                    'colors' => 'diatonic',
                    'notes' => array_map(fn($f) => $f['coord'] . '-' . $f['pc'], $result),
                    'root' => $lowest
                ]); ?>
            <?php endforeach; ?>
        <?php endforeach; ?>        
    <?php endif; ?>

    <h2>Griffbrett</h3>

    <p>Alle Noten <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> mit Grundton <?= $root ?> auf dem Griffbrett bis zum zwölften Bund:</p>

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

    <p>Alle Noten <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> mit Grundton <?= $root ?> in der Intervallschrift bis zum zwölften Bund:</p>

    <?php
    echo app\widgets\Fretboard::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['label'], $fingerings),
        'root' => $rootFingering
    ]);
    ?>

    <p>Alle Noten <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> mit Grundton <?= $root ?> in der vereinfachten Intervallschrift bis zum zwölften Bund:</p>

    <?php
    echo app\widgets\Fretboard::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $model::convertNotesToOldFormat($note['label']), $fingerings),
        'root' => $rootFingering
    ]);
    ?>

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

    <?= Comments::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

</div>

<?= Hits::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

<?php if (!empty($similars)): ?>
    <?php $this->beginBlock('sidebar') ?>
    <div class="sidebarWidget">
        <h3 class="sidebarWidget__title">Fingersätze</h3>
        <ul class="sidebarWidget__list">
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= Url::to(['/fingering/index']) ?>#intervall">Intervalle</a>
            </li>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= Url::to(['/fingering/index']) ?>#akkord">Akkorde & Arpeggios</a>
            </li>
            <li class="sidebarWidget__item">
                <a class="sidebarWidget__link" href="<?= Url::to(['/fingering/index']) ?>#tonleiter">Tonleitern</a>
            </li>
        </ul>
    </div>
    <?php $this->endBlock() ?>
<?php endif; ?>

<style>
    .fretboard {
        margin-bottom: 2rem;
    }    
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
