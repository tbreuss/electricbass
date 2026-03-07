<?php

/**
 * @var yii\web\View $this
 * @var app\features\fingering\models\Fingering[] $modelsPerCategory
 * @var app\features\fingering\models\Fingering $model
 * @var string $root
 * @var string $strings
 * @var string $expand
 */

use app\features\rating\RatingShare;
use app\helpers\Html;
use app\widgets\Hits;
use yii\helpers\Markdown;

$this->blocks['title'] = $model->title;
$this->title = $model->title . ' | Fingersätze';
$this->params['breadcrumbs'][] = ['label' => 'Werkzeuge', 'url' => '/tools'];
$this->params['breadcrumbs'][] = ['label' => 'Fingersätze', 'url' => ['fingering/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->category), 'url' => ['fingering/index', 'category' => $model->category]];
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

<h1><?= $model->title ?></h1>

<div class="input-group d-block d-md-none">
    <div><?= Yii::t('app', $model->category) ?></div>
    <select class="form-select" onchange="window.location.href = this.value">
        <?php foreach ($modelsPerCategory as $modelPerCategory): ?>
            <option value="<?= $modelPerCategory->url ?>" <?= $model->url === $modelPerCategory->url ? 'selected' : '' ?>><?= $modelPerCategory->title ?></option>
        <?php endforeach ?>
    </select>
</div>

<?php $this->beginBlock('sidebar') ?>
<script>
    document.querySelector('.sidebar__inner').style.visibility = 'hidden';
    window.addEventListener('load', function() {
        loadPartial('<?= app\helpers\Url::to(['/fingering/table-of-contents']) ?>', { category: '<?= $model->category ?>' }, '.sidebar__inner');
    });
</script>
<?php $this->endBlock() ?>

    <div class="fretboardForm">
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormRoot">Grundton</label>
            <?php $roots = ['C', 'Db', 'D', 'Eb', 'E', 'F', 'F#', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B'] ?>
            <?php echo Html::dropDownList('root', $root, array_combine($roots, $roots), ['id' => 'fretboardFormRoot', 'class' => 'fretboardForm__dropdown', 'onchange' => 'inputChanged();']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormStrings">Anzahl Saiten</label>
            <?php echo Html::dropDownList('strings', $strings, ['4' => '4','5' => '5','6' => '6'], ['id' => 'fretboardFormStrings', 'class' => 'fretboardForm__dropdown', 'onchange' => 'inputChanged();']) ?>
        </div>
        <div class="fretboardForm__column">
            <label class="fretboardForm__label" for="fretboardFormExpand">Erweiterte Lage</label>
            <?php echo Html::dropDownList('expand', $expand, ['0' => 'Nein', '1' => 'Ja'], ['id' => 'fretboardFormExpand', 'class' => 'fretboardForm__dropdown', 'onchange' => 'inputChanged();']) ?>
        </div>
    </div>

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
        if (abs(tebe\tonal\note\get($transposed)->alt) > 1) {
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
                    ?>Akkord<?php
                endif; ?>
                <?php if ($model->category === 'arpeggio'):
                    ?>Arpeggio<?php
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
    <h2>Fingersatz</h2>
    <p>Der gängigste Fingersatz <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> lautet wie folgt:</p>
    <?= app\features\fingering\Widget::widget([
            'showDots' => false,
            'showFretNumbers' => false,
            'showStringNames' => false,
            'colors' => 'default',
            'strings' => ['G', 'D', 'A', 'E'],
            'frets' => range(0, 12),
            'notes' => preg_split('/\s+/', $model->fingering),
    ]); ?>
<?php endif; ?>

<h2>Griffbrett</h2>

<p>Alle Töne <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> auf dem Griffbrett bis zum zwölften Bund:</p>

<?php

$fingerings = tebe\tonal\fretboard\findNotes($TUNING, $notes);

echo app\features\fingering\Widget::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['note'], $fingerings),
        'root' => $rootFingering
]);
?>

<p>Alle Töne <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> in Intervallschrift auf dem Griffbrett bis zum zwölften Bund:</p>

<?php
echo app\features\fingering\Widget::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $note['label'], $fingerings),
        'root' => $rootFingering
]);
?>

<p>Alle Töne <?= $model->title_genitive ? $model->title_genitive : $model->categoryAsGenitive() ?> in vereinfachter Intervallschrift auf dem Griffbrett bis zum zwölften Bund:</p>

<?php
echo app\features\fingering\Widget::widget([
        'colors' => 'diatonic',
        'strings' => $FRETBOARD_STRINGS,
        'frets' => $FRETBOARD_FRETS,
        'notes' => array_map(fn($note) => $note['coord'] . '-' . $model::convertNotesToOldFormat($note['label']), $fingerings),
        'root' => $rootFingering
]);
?>

    <?php

    $allPossibilitites = [];
    $expandPosition = (int)$expand;
    foreach (range(1, 8) as $pos) {
        $possibilities = tebe\tonal\fretboard\get_all_possibilities($notes, $fingerings, $pos, $expandPosition);
        if (!empty($possibilities)) {
            $allPossibilitites[$pos] = $possibilities;
        }
    }
    ?>

    <?php if (count($allPossibilitites) == 0): ?>
        <h2>Lagen</h2>
        <p>
            Für <?= $model->title_accusative ? $model->title_accusative : $model->categoryAsAccusative() ?> mit Grundton <?= $root ?> auf dem <?= $strings ?>-saitigen E-Bass wurde kein Fingersatz gefunden.
            In diesem Fall empfiehlt sich die Nutzung der erweiterten Lage.
        </p>
    <?php else: ?>
        <h2>Lagen</h2>
        <p><?= ucfirst($model->title_nominative) ?> mit Grundton <?= $root ?> lässt sich auf dem <?= $strings ?>-saitigen E-Bass in folgenden Lagen greifen:</p>
        <?php foreach ($allPossibilitites as $pos => $possibilitiesPerPosition): ?>
            <?php foreach ($possibilitiesPerPosition as $result): ?>
                <?= app\features\fingering\Widget::widget([
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

    <?php if (!empty($model->abstract)): ?>
        <div class="markdown"><?= Markdown::process($model->abstract) ?></div>
    <?php endif; ?>

    <?= $this->render('@app/features/_partials/meta', [
        'categories' => [
            ['label' => 'Werkzeuge', 'url' => ['/tools']],
            ['label' => 'Fingersätze', 'url' => ['/fingering/index']]
        ],
        'tags' => $model->tags,
    ]); ?>

<?= RatingShare::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

<?= app\features\comment\Widget::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

<?= Hits::widget(["tableName" => "fingering", "tableId" => $model->id]) ?>

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
<script>
    function inputChanged() {
        const root = document.getElementById('fretboardFormRoot').value;
        const strings = document.getElementById('fretboardFormStrings').value;
        const expand = document.getElementById('fretboardFormExpand').value;
        window.location.href = '#root=' + root + '&strings=' + strings + '&expand=' + expand;
    }

    function sendForm(hash) {
        const params = new URLSearchParams(hash.substring(1));
        if (!(params.has('root') && params.has('strings') && params.has('expand'))) {
            return;
        }

        // Create a form
        const form = document.createElement("form");
        form.method = "POST";

        const csrfInput = document.createElement("input");
        csrfInput.type = "text";
        csrfInput.name = "_csrf";
        csrfInput.value = document.head.querySelector("[name~=csrf-token][content]").content;
        form.appendChild(csrfInput);

        const rootInput = document.createElement("input");
        rootInput.type = "text";
        rootInput.name = "root";
        rootInput.value = params.get('root');
        form.appendChild(rootInput);

        const stringsInput = document.createElement("input");
        stringsInput.type = "text";
        stringsInput.name = "strings";
        stringsInput.value = params.get('strings');
        form.appendChild(stringsInput);

        const expandInput = document.createElement("input");
        expandInput.type = "text";
        expandInput.name = "expand";
        expandInput.value = params.get('expand');
        form.appendChild(expandInput);

        document.body.appendChild(form);

        form.submit();
    }

    window.addEventListener("hashchange", (event) => {
        const hashPart = event.newURL.split('#');
        if (hashPart[1]) {
            sendForm(location.hash);
        }
    });

    const loadedHash = '#root=<?= $root ?>&strings=<?= $strings ?>&expand=<?= $expand ?>';
    if ((location.hash !== '') && (loadedHash !== location.hash)) {
        sendForm(location.hash);
    }
</script>
