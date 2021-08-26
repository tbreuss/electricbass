<?php

/**
 * @var yii\web\View $this
 */

use app\helpers\Html;
use app\widgets\SocialBar;

$this->params['pageTitle'] = 'Leeres Notenpapier als PDF herunterladen und ausdrucken';
$this->params['metaDescription'] = 'Blanko Notenpapier zum Herunterladen und Ausdrucken ✓ PDFs mit Notenlinien ✚ Für alle Instrumente ✚ Für E-Bass und Gitarre mit Tabulatur ➨ Gratis!';
$this->params['breadcrumbs'][] = ['label' => 'Tools', 'url' => ['tool/index']];
$this->params['breadcrumbs'][] = 'Leeres Notenpapier als PDF';
?>

<div class="content">

    <h1>Notenpapier zum Herunterladen und Ausdrucken</h1>

    <p class="lead">
        Blanko Notenpapier für Bass, Gitarre, Klavier und andere Instrumente!
        Lade die Notenblätter im PDF-Format kostenlos herunter und drucke sie anschliessend auf deinem Drucker aus.
        Schreibe damit Musiknoten, Gesangs- und Basslinien oder Leadsheets für deine Band.
        Die Notenblätter eignen sich ideal zum Transkribieren, Komponieren, Arrangieren, Notizen erstellen oder für andere Zwecke.
        Das alles gratis und ohne Kosten!
    </p>

    <?php
    /** @var array<string|string[]> */
    $papers = [
        'Für E-Bassisten',
        [
            'notenpapier-bassschluessel-tabulatur-4-saiter.pdf',
            '4-Saiter Bass TAB Notenpapier',
            'PDF',
            'Für Bassisten: Sechs Systeme mit Bassschlüssel und Tabulatur für 4-Saiter. DIN A4-Format.'
        ],
        [
            'notenpapier-bassschluessel-tabulatur-5-saiter.pdf',
            '5-Saiter Bass TAB Notenpapier',
            'PDF',
            'Für Bassisten: Sechs Systeme mit Bassschlüssel und Tabulatur für 5-Saiter. DIN A4-Format.'
        ],
        [
            'notenpapier-bassschluessel-tabulatur-6-saiter.pdf',
            '6-Saiter Bass TAB Notenpapier',
            'PDF',
            'Für Bassisten: Sechs Systeme mit Bassschlüssel und Tabulatur für 6-Saiter. DIN A4-Format.'
        ],
        [
            'notenpapier-tabulatur-4-saiter.pdf',
            '4-Saiter Tabulatur Notenpapier',
            'PDF',
            'Für Bassisten: Zwölf Systeme mit Tabulatur für 4-saitige Instrumente. DIN A4-Format.'
        ],
        [
            'notenpapier-tabulatur-5-saiter.pdf',
            '5-Saiter Tabulatur Notenpapier',
            'PDF',
            'Für Bassisten: Zwölf Systeme mit Tabulatur für 5-saitige Instrumente. DIN A4-Format.'
        ],
        [
            'notenpapier-tabulatur-6-saiter.pdf',
            '6-Saiter Tabulatur Notenpapier',
            'PDF',
            'Für Bassisten: Zehn Systeme mit Tabulatur für 6-saitige Instrumente. DIN A4-Format.'
        ],
        'Für andere Bassisten',
        [
            'notenpapier-bassschluessel.pdf',
            'Leeres Bassschlüssel Notenpapier',
            'PDF',
            'Für Bassisten und Bass-Instrumentalisten: Leeres Notenpapier mit zwölf Systemen und Bassschlüssel. DIN A4-Format.'
        ],
        [
            'notenpapier-bassschluessel-32-takte.pdf',
            'Leeres Bassschlüssel Notenpapier 32 Takte',
            'PDF',
            'Für Bassisten und Bass-Instrumentalisten: Leeres Notenpapier mit acht Systemen und vier Takten pro System im
            Bassschlüssel. Dieses Notenpapier eignet sich ideal für Jazz-Standards in 32-taktigen Formen. Ein Chorus (in der
            Form AABA oder ABAB) passt somit genau auf ein Notenblatt. Schreibe deine Walking Basslines oder transkribiere
            abgefahrene Basssolos. DIN A4-Format.'
        ],
        [
            'notenpapier-bassschluessel-taktstriche.pdf',
            'Leeres Bassschlüssel Notenpapier',
            'PDF',
            'Für Bassisten und Bass-Instrumentalisten: Leeres Notenpapier mit zwölf Systemen und vier Takten pro System im
            Bassschlüssel. Dieses Notenpapier ist perfekt, um zum Beispiel Walking Basslines zu transkribieren. DIN
            A4-Format.'
        ],
        'Für Gitarristen',
        [
            'notenpapier-gitarre-tabulatur.pdf',
            'Gitarre TAB Notenpapier',
            'PDF',
            'Für Gitarristen: Sechs Systeme mit Violinschlüssel und Tabulatur für Gitarre. DIN A4-Format.'
        ],
        'Für Pianisten',
        [
            'notenpapier-klavier.pdf',
            'Klavier Notenpapier',
            'PDF',
            'Für Pianisten: Leeres Notenpapier für Klavier mit sechs Systemen (Violin- und Basschlüssel). DIN A4-Format.'
        ],
        'Für jedermann',
        [
            'notenpapier.pdf',
            'Leeres Notenpapier',
            'PDF',
            'Für jedermann: Leeres Notenpapier mit zwölf Systemen. DIN A4-Format.'
        ],
        [
            'notenpapier-violinschluessel.pdf',
            'Leeres Violinschlüssel Notenpapier',
            'PDF',
            'Für jedermann: Leeres Notenpapier mit Violinschlüssel und zwölf Systemen. DIN A4-Format.'
        ],
        [
            'notenpapier-violinschluessel-taktstriche.pdf',
            'Leeres Violinschlüssel Notenpapier mit Takten',
            'PDF',
            'Für jedermann: Leeres Notenpapier mit Violinschlüssel und zwölf Systemen à vier Takte pro System. Perfekt für
            Transkriptionen von Jazzsongs oder um einfach Melodien zu notieren. DIN A4-Format.'
        ],
    ];
    ?>

    <div class="widget widget-listview">
    <?php foreach ($papers as $paper): ?>
        <?php if (is_string($paper)): ?>
            <h2><?= $paper ?></h2>
        <?php else: ?>
            <?php
                $urlPdf = Yii::getAlias('@web/media/tools/notenpapier/' . $paper[0]);
            if ($urlPdf === false) {
                continue;
            }
                $urlImg = str_replace('.pdf', '.png', $urlPdf);
            ?>
            <div class="row" style="margin-bottom:20px">
                <div class="col-sm-3">
                    <img class="img-fluid" src="<?= $urlImg ?>" width="200" alt="<?= $paper[1] ?>">
                </div>
                <div class="col-sm-9">
                    <h3 style="margin-top:0"><?= Html::a($paper[1] . ' (' . $paper[2] . ')', $urlPdf, ['target' => '_blank']) ?></a></h3>
                    <p><?= $paper[3] ?></p>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>

    <?= SocialBar::widget(["text" => "Notenpapier zum Herunterladen und Ausdrucken"]) ?>

</div>
